<?php
/**
 * TeDotamos: optimización del buscador (desplegable del header y página de resultados).
 *
 * Qué hace:
 * 1. Valida el término antes de consultar la base de datos: 2–80 caracteres. Un
 *    término de una letra recorría todo el catálogo y tardaba ~2 s.
 * 2. Cachea la respuesta del desplegable unos minutos (transient). La caché se
 *    invalida sola al guardar productos, precios o stock, y al cambiar estas opciones.
 * 3. Reduce el trabajo por tecla: las consultas de SKU (`LIKE '%…%'` sobre postmeta)
 *    solo corren si el término parece un SKU, y el desplegable busca solo en el
 *    título (la página de resultados mantiene las columnas del Customizer).
 * 4. Acota los IDs de SKU de la página de resultados (`LIMIT`) para evitar scans
 *    completos de `wp_postmeta`.
 *
 * Opciones en Apariencia → TeDotamos → General.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Search {

	/** Longitud mínima y máxima del término, en caracteres. */
	const MIN_TERM = 2;
	const MAX_TERM = 80;

	/** Tope de resultados del desplegable, aunque el Customizer pida más. */
	const MAX_RESULTS = 20;

	/** Tope de IDs que aporta cada consulta de SKU. */
	const MAX_SKU_IDS = 100;

	/** Se incrementa para invalidar todas las respuestas cacheadas. */
	const CACHE_VERSION = 'tedotamos_search_cache_version';

	/** Formato del HTML del desplegable; va en la clave de caché. */
	const DROPDOWN_FORMAT = 2;

	/** Títulos que se leen para sacar sugerencias y categorías. */
	const SUGGEST_SCAN = 40;

	/** Máximo de sugerencias de búsqueda y de categorías. */
	const MAX_TERMS = 6;
	const MAX_CATS  = 4;

	/** Palabras que no cuentan como parte "útil" de una sugerencia. */
	const STOPWORDS = array( 'de', 'del', 'la', 'el', 'los', 'las', 'para', 'con', 'sin', 'en', 'y', 'o', 'x', 'a', 'por', 'un', 'una', 'al' );

	public static function init() {
		// Prioridad 1: corre antes que Martfury (10). Valida el término y sirve la caché.
		add_action( 'wc_ajax_martfury_search_products', array( __CLASS__, 'maybe_serve_cached' ), 1 );

		// Menos trabajo en cada consulta del desplegable.
		add_filter( 'martfury_get_search_products_query', array( __CLASS__, 'tune_products_query' ) );
		add_filter( 'martfury_get_search_products_sku_query', array( __CLASS__, 'tune_sku_query' ) );
		add_filter( 'martfury_get_search_products_variation_sku_query', array( __CLASS__, 'tune_variation_sku_query' ) );
		add_filter( 'martfury_get_search_posts_query', array( __CLASS__, 'tune_posts_query' ) );

		// La caché se invalida al cambiar productos, precios o stock.
		foreach ( array(
			'woocommerce_update_product',
			'woocommerce_update_product_variation',
			'woocommerce_product_set_stock',
			'woocommerce_variation_set_stock',
			'woocommerce_product_set_stock_status',
			'woocommerce_variation_set_stock_status',
			'save_post_product',
			'save_post_product_variation',
			'deleted_post',
			'trashed_post',
			'untrashed_post',
		) as $hook ) {
			add_action( $hook, array( __CLASS__, 'maybe_bump_cache_version' ) );
		}
	}

	// ------------------------------------------------------------------ opciones

	private static function enabled( $key ) {
		if ( ! class_exists( 'TeDotamos_Admin' ) ) {
			return false;
		}

		return (bool) TeDotamos_Admin::get_option( $key );
	}

	private static function cache_ttl() {
		if ( ! class_exists( 'TeDotamos_Admin' ) ) {
			return 0;
		}

		$minutes = (int) TeDotamos_Admin::get_option( 'search_cache_ttl' );

		return max( 0, min( 60, $minutes ) ) * MINUTE_IN_SECONDS;
	}

	// ------------------------------------------------------------------ endpoint AJAX

	/**
	 * Valida el término y, si hay copia cacheada, responde sin tocar la base de datos.
	 * Si no hay caché, deja que siga el handler de Martfury.
	 */
	public static function maybe_serve_cached() {
		if ( apply_filters( 'martfury_check_ajax_referer', true ) ) {
			check_ajax_referer( '_martfury_nonce', 'nonce' );
		}

		$term = self::request_term();

		if ( ! self::is_valid_term( $term ) ) {
			self::send_nothing_found();
		}

		if ( ! self::enabled( 'search_cache' ) ) {
			return;
		}

		$cached = get_transient( self::cache_key( $term ) );
		if ( is_string( $cached ) && '' !== $cached ) {
			wp_send_json_success( $cached );
		}
	}

	/**
	 * Guarda la respuesta que va a enviar Martfury. Se llama desde
	 * Martfury_WooCommerce::instance_search_result() (ver inc/frontend/woocommerce.php).
	 *
	 * @param string $html        Lista <ul>…</ul> ya renderizada.
	 * @param bool   $has_results Si es false (solo "Nothing found") no se guarda: no
	 *                            aporta y así no se crean filas de transient por cada
	 *                            término raro que llegue al buscador.
	 */
	public static function store_cached_response( $html, $has_results = true ) {
		if ( ! $has_results || ! self::enabled( 'search_cache' ) || ! is_string( $html ) || '' === $html ) {
			return;
		}

		$term = self::request_term();
		if ( ! self::is_valid_term( $term ) ) {
			return;
		}

		$ttl = self::cache_ttl();
		if ( $ttl < 1 ) {
			return;
		}

		set_transient( self::cache_key( $term ), $html, $ttl );
	}

	/**
	 * Invalida todas las respuestas cacheadas.
	 */
	public static function bump_cache_version() {
		update_option( self::CACHE_VERSION, time(), false );
	}

	/**
	 * Algunos hooks de WooCommerce pasan un WC_Product en lugar del ID
	 * (woocommerce_product_set_stock, woocommerce_variation_set_stock).
	 *
	 * @param int|WP_Post|WC_Product $post ID o producto.
	 */
	public static function maybe_bump_cache_version( $post = 0 ) {
		if ( is_object( $post ) ) {
			if ( method_exists( $post, 'get_id' ) ) {
				$post = (int) $post->get_id();
			} elseif ( isset( $post->ID ) ) {
				$post = (int) $post->ID;
			} else {
				$post = 0;
			}
		} else {
			$post = (int) $post;
		}

		if ( $post && ( wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) ) ) {
			return;
		}

		self::bump_cache_version();
	}

	private static function send_nothing_found() {
		wp_send_json_success( sprintf( '<ul><li>%s</li></ul>', esc_html__( 'Nothing found', 'martfury' ) ) );
	}

	private static function request_term() {
		if ( ! isset( $_POST['term'] ) || ! is_string( $_POST['term'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return '';
		}

		$term = sanitize_text_field( wp_unslash( $_POST['term'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		$term = preg_replace( '/\s+/u', ' ', $term );

		return trim( (string) $term ); // (string): preg_replace puede devolver null con UTF-8 inválido.
	}

	private static function is_valid_term( $term ) {
		$length = function_exists( 'mb_strlen' ) ? mb_strlen( $term, 'UTF-8' ) : strlen( $term );

		return $length >= self::MIN_TERM && $length <= self::MAX_TERM;
	}

	private static function cache_key( $term ) {
		$cat  = isset( $_POST['cat'] ) && is_scalar( $_POST['cat'] ) ? sanitize_title( wp_unslash( $_POST['cat'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$type = isset( $_POST['search_type'] ) && is_scalar( $_POST['search_type'] ) ? sanitize_key( wp_unslash( $_POST['search_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		$parts = array(
			'v'    => (int) get_option( self::CACHE_VERSION, 1 ),
			'term' => function_exists( 'mb_strtolower' ) ? mb_strtolower( $term, 'UTF-8' ) : strtolower( $term ),
			'cat'  => $cat,
			'type' => $type,
			'num'  => (int) martfury_get_option( 'header_ajax_search_results_number' ),
			'cur'  => function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : '',
			'lang' => defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : get_locale(),
			'user' => get_current_user_id(),
			'fmt'  => self::suggestions_enabled() ? self::DROPDOWN_FORMAT : 1,
		);

		return 'tdts_' . md5( wp_json_encode( $parts ) );
	}

	// ------------------------------------------------------------------ desplegable con sugerencias

	public static function suggestions_enabled() {
		return self::enabled( 'search_suggestions' );
	}

	/**
	 * Arma el desplegable estilo "sugerencias + categorías + productos". Se llama desde
	 * Martfury_WooCommerce::instance_search_result() con los <li> de productos de Martfury.
	 *
	 * @param string[] $items <li> de productos ya renderizados.
	 * @return array{0:string,1:bool} HTML y si hay algo que mostrar (para la caché).
	 */
	public static function render_dropdown( array $items ) {
		$term    = self::request_term();
		$context = self::suggestion_context( $term );
		$search  = self::search_url( $term );

		$side = '';
		if ( $context['terms'] ) {
			$side .= self::render_group( esc_html__( 'Sugerencias', 'martfury' ), 'tdt-sr__list tdt-sr__terms', $context['terms'] );
		}
		if ( $context['cats'] ) {
			$side .= self::render_group( esc_html__( 'Categorías', 'martfury' ), 'tdt-sr__list tdt-sr__cats', $context['cats'] );
		}

		if ( $items ) {
			$items = array_map( function ( $item ) use ( $term ) {
				return self::highlight_product_title( $item, $term );
			}, $items );

			$main = sprintf(
				'<p class="tdt-sr__title">%s</p><ul class="tdt-sr__products">%s</ul>',
				esc_html__( 'Productos', 'martfury' ),
				implode( '', $items )
			);
		} else {
			$main = sprintf(
				'<p class="tdt-sr__empty">%s</p>',
				/* translators: %s: término buscado */
				sprintf( esc_html__( 'No encontramos productos para «%s».', 'martfury' ), esc_html( $term ) )
			);
		}

		$main .= sprintf(
			'<a class="tdt-sr__all" href="%s">%s <span aria-hidden="true">→</span></a>',
			esc_url( $search ),
			$items
				/* translators: %s: término buscado */
				? sprintf( esc_html__( 'Ver todos los resultados para «%s»', 'martfury' ), esc_html( $term ) )
				/* translators: %s: término buscado */
				: sprintf( esc_html__( 'Buscar «%s» en toda la tienda', 'martfury' ), esc_html( $term ) )
		);

		$html = sprintf(
			'<div class="tdt-sr%s">%s<div class="tdt-sr__main">%s</div></div>',
			$side ? '' : ' tdt-sr--single',
			$side ? '<div class="tdt-sr__side">' . $side . '</div>' : '',
			$main
		);

		return array( $html, ! empty( $items ) || '' !== $side );
	}

	private static function render_group( $title, $class, array $rows ) {
		return sprintf( '<div class="tdt-sr__group"><p class="tdt-sr__title">%s</p><ul class="%s">%s</ul></div>', $title, esc_attr( $class ), implode( '', $rows ) );
	}

	/**
	 * Sugerencias de búsqueda y categorías a partir de los títulos que coinciden.
	 *
	 * @return array{terms:string[],cats:string[]} Filas <li> ya renderizadas.
	 */
	private static function suggestion_context( $term ) {
		$out = array( 'terms' => array(), 'cats' => array() );

		if ( ! self::is_valid_term( $term ) ) {
			return $out;
		}

		$args = array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			'posts_per_page'         => self::SUGGEST_SCAN,
			's'                      => $term,
			'search_columns'         => array( 'post_title' ),
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		);

		if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
			$visibility          = wc_get_product_visibility_term_ids();
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $visibility['exclude-from-search'],
				'operator' => 'NOT IN',
			);
		}

		$cat = self::request_cat();
		if ( $cat ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $cat,
			);
		}

		$posts = get_posts( apply_filters( 'tedotamos_search_suggestions_query', $args, $term ) );

		// 1. Sugerencias: el término + las palabras que lo siguen en los títulos.
		$phrases = array();
		foreach ( $posts as $post ) {
			$phrase = self::phrase_from_title( $post->post_title, $term );
			if ( null === $phrase ) {
				continue;
			}
			$key = self::lower( $phrase[0] . $phrase[1] );
			if ( ! isset( $phrases[ $key ] ) ) {
				$phrases[ $key ] = array( 'typed' => $phrase[0], 'rest' => $phrase[1], 'n' => 0 );
			}
			$phrases[ $key ]['n']++;
		}

		uasort( $phrases, function ( $a, $b ) {
			return $b['n'] - $a['n'] ?: strlen( $a['typed'] . $a['rest'] ) - strlen( $b['typed'] . $b['rest'] );
		} );

		foreach ( array_slice( $phrases, 0, self::MAX_TERMS ) as $phrase ) {
			$out['terms'][] = sprintf(
				'<li><a href="%s"><span class="tdt-sr__ico" aria-hidden="true"></span><span>%s<b>%s</b></span></a></li>',
				esc_url( self::search_url( $phrase['typed'] . $phrase['rest'] ) ),
				esc_html( $phrase['typed'] ),
				esc_html( $phrase['rest'] )
			);
		}

		// 2. Categorías cuyo nombre coincide (enlace directo a la categoría).
		$seen     = array();
		$excluded = array( (int) get_option( 'default_product_cat' ) );
		$named    = get_terms( array(
			'taxonomy'   => 'product_cat',
			'name__like' => $term,
			'hide_empty' => true,
			'number'     => self::MAX_CATS,
			'orderby'    => 'count',
			'order'      => 'DESC',
		) );

		if ( ! is_wp_error( $named ) ) {
			foreach ( $named as $t ) {
				if ( in_array( (int) $t->term_id, $excluded, true ) ) {
					continue;
				}
				$link = get_term_link( $t );
				if ( is_wp_error( $link ) ) {
					continue;
				}
				$seen[ $t->term_id ] = true;
				$out['cats'][]       = sprintf(
					'<li><a href="%s"><span class="tdt-sr__ico tdt-sr__ico--cat" aria-hidden="true"></span><span>%s</span></a></li>',
					esc_url( $link ),
					self::highlight( $t->name, $term )
				);
			}
		}

		// 3. "término en Categoría": categorías más frecuentes entre los productos encontrados.
		$room = self::MAX_CATS - count( $out['cats'] );
		if ( $room > 0 && $posts && ! $cat ) {
			$terms = wp_get_object_terms( wp_list_pluck( $posts, 'ID' ), 'product_cat', array( 'fields' => 'all_with_object_id' ) );
			$count = array();
			$by_id = array();
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $t ) {
					if ( isset( $seen[ $t->term_id ] ) || in_array( (int) $t->term_id, $excluded, true ) ) {
						continue;
					}
					$count[ $t->term_id ] = isset( $count[ $t->term_id ] ) ? $count[ $t->term_id ] + 1 : 1;
					$by_id[ $t->term_id ] = $t;
				}
			}
			arsort( $count );

			foreach ( array_slice( array_keys( $count ), 0, $room ) as $term_id ) {
				$t             = $by_id[ $term_id ];
				$out['cats'][] = sprintf(
					'<li><a href="%s"><span class="tdt-sr__ico" aria-hidden="true"></span><span>%s <span class="tdt-sr__in">%s</span> <b>%s</b></span></a></li>',
					esc_url( self::search_url( $term, $t->slug ) ),
					esc_html( $term ),
					esc_html__( 'en', 'martfury' ),
					esc_html( $t->name )
				);
			}
		}

		return apply_filters( 'tedotamos_search_suggestions', $out, $term, $posts );
	}

	/**
	 * Del título "Guante de hilaza Importada blanco GUA-M10" y el término "guan"
	 * saca array( 'guan', 'te de hilaza' ): lo que escribió y cómo sigue (hasta una
	 * palabra útil más; se corta en números, medidas o SKU).
	 *
	 * @return array{0:string,1:string}|null
	 */
	public static function phrase_from_title( $title, $term ) {
		$title = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( html_entity_decode( (string) $title, ENT_QUOTES, 'UTF-8' ) ) ) );
		$term  = trim( (string) $term );
		if ( '' === $title || '' === $term || ! function_exists( 'mb_strpos' ) ) {
			return null;
		}

		// Posición sin tildes ni mayúsculas ("camara" encuentra "Cámara").
		$hay    = self::fold( $title );
		$needle = self::fold( $term );
		if ( self::len( $hay ) !== self::len( $title ) ) {
			$hay    = self::lower( $title );
			$needle = self::lower( $term );
		}
		$pos = mb_strpos( $hay, $needle, 0, 'UTF-8' );
		if ( false === $pos ) {
			return null;
		}

		// Empieza en el inicio de la palabra donde está el término.
		$start = $pos;
		while ( $start > 0 && ! preg_match( '/[\s\(\[\/,;:]/u', mb_substr( $title, $start - 1, 1, 'UTF-8' ) ) ) {
			$start--;
		}
		$typed_end = $pos + self::len( $term );
		$typed     = self::lower( mb_substr( $title, $start, $typed_end - $start, 'UTF-8' ) );
		$after     = mb_substr( $title, $typed_end, null, 'UTF-8' );

		// Resto de la palabra en curso ("guan" → "te").
		preg_match( '/^[^\s\(\)\[\]\/,;:]*/u', $after, $m );
		$rest  = self::lower( $m[0] );
		$after = mb_substr( $after, self::len( $m[0] ), null, 'UTF-8' );

		if ( preg_match( '/\d/', $typed . $rest ) ) {
			return null; // El término cae en un código o medida: no es una sugerencia útil.
		}

		$extra  = array();
		$useful = 0;
		foreach ( preg_split( '/\s+/u', trim( $after ) ) as $word ) {
			if ( '' === $word || preg_match( '/[\d\(\)\[\]\/,;:"+|]/u', $word ) || self::len( $word ) > 20 ) {
				break;
			}
			$word    = self::lower( trim( $word, '.-–' ) );
			if ( '' === $word ) {
				break;
			}
			$extra[] = $word;
			if ( ! in_array( $word, self::STOPWORDS, true ) && ++$useful >= 1 ) {
				break;
			}
			if ( count( $extra ) >= 4 ) {
				break;
			}
		}
		// Que no termine en "de", "para"…
		while ( $extra && in_array( end( $extra ), self::STOPWORDS, true ) ) {
			array_pop( $extra );
		}

		$rest .= $extra ? ' ' . implode( ' ', $extra ) : '';
		if ( '' === trim( $rest ) ) {
			return null; // Igual a lo que escribió: no aporta.
		}

		return array( $typed, $rest );
	}

	/**
	 * Resalta el término en el título de cada <li> de producto de Martfury.
	 */
	private static function highlight_product_title( $item, $term ) {
		return preg_replace_callback(
			'/(<a class="title-item"[^>]*>)(.*?)(<\/a>)/su',
			function ( $m ) use ( $term ) {
				if ( false !== strpos( $m[2], '<' ) ) {
					return $m[0];
				}
				return $m[1] . self::highlight( html_entity_decode( $m[2], ENT_QUOTES, 'UTF-8' ), $term ) . $m[3];
			},
			$item
		);
	}

	/**
	 * Escapa $text y envuelve en <mark> cada palabra del término (sin tildes ni mayúsculas).
	 */
	public static function highlight( $text, $term ) {
		$text  = (string) $text;
		$words = array_filter( preg_split( '/\s+/u', self::fold( $term ) ), function ( $w ) {
			return self::len( $w ) >= 2;
		} );
		$folded = self::fold( $text );
		if ( ! $words || ! function_exists( 'mb_strpos' ) || self::len( $folded ) !== self::len( $text ) ) {
			return esc_html( $text );
		}

		// Rangos a marcar sobre el texto sin tildes; se aplican al original.
		$marks = array_fill( 0, self::len( $text ), false );
		foreach ( $words as $w ) {
			$offset = 0;
			while ( false !== ( $p = mb_strpos( $folded, $w, $offset, 'UTF-8' ) ) ) {
				for ( $i = $p, $end = $p + self::len( $w ); $i < $end; $i++ ) {
					$marks[ $i ] = true;
				}
				$offset = $p + 1;
			}
		}

		$out  = '';
		$open = false;
		foreach ( preg_split( '//u', $text, -1, PREG_SPLIT_NO_EMPTY ) as $i => $char ) {
			if ( $marks[ $i ] && ! $open ) {
				$out .= '<mark>';
				$open = true;
			} elseif ( ! $marks[ $i ] && $open ) {
				$out .= '</mark>';
				$open = false;
			}
			$out .= esc_html( $char );
		}

		return $out . ( $open ? '</mark>' : '' );
	}

	private static function search_url( $term, $cat = '' ) {
		$args = array(
			's'         => rawurlencode( $term ),
			'post_type' => 'product',
		);
		if ( '' !== $cat ) {
			$args['product_cat'] = rawurlencode( $cat );
		}

		return add_query_arg( $args, home_url( '/' ) );
	}

	private static function request_cat() {
		$cat = isset( $_POST['cat'] ) && is_scalar( $_POST['cat'] ) ? sanitize_title( wp_unslash( $_POST['cat'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		return '0' === $cat ? '' : $cat;
	}

	private static function lower( $s ) {
		return function_exists( 'mb_strtolower' ) ? mb_strtolower( (string) $s, 'UTF-8' ) : strtolower( (string) $s );
	}

	private static function fold( $s ) {
		return self::lower( function_exists( 'remove_accents' ) ? remove_accents( (string) $s ) : (string) $s );
	}

	private static function len( $s ) {
		return function_exists( 'mb_strlen' ) ? mb_strlen( (string) $s, 'UTF-8' ) : strlen( (string) $s );
	}

	// ------------------------------------------------------------------ consultas

	public static function tune_products_query( $args ) {
		$args = self::base_tuning( $args );

		// El desplegable es un atajo: buscar en el contenido recorre textos largos y
		// no aporta en una lista de 7 resultados. Enter sigue buscando en todo.
		if ( self::enabled( 'search_ajax_title_only' ) ) {
			$args['search_columns'] = array( 'post_title' );
		}

		return $args;
	}

	public static function tune_sku_query( $args ) {
		return self::tune_sku( $args );
	}

	public static function tune_variation_sku_query( $args ) {
		return self::tune_sku( $args );
	}

	public static function tune_posts_query( $args ) {
		return self::base_tuning( $args );
	}

	private static function tune_sku( $args ) {
		$args = self::base_tuning( $args );

		if ( self::enabled( 'search_sku_heuristic' ) && ! self::looks_like_sku( self::request_term() ) ) {
			// Sin SKU posible: consulta trivial en vez de dos LIKE sobre wp_postmeta.
			$args['post__in']       = array( 0 );
			$args['posts_per_page'] = 1;
		}

		return $args;
	}

	private static function base_tuning( $args ) {
		$args = (array) $args;

		if ( empty( $args['posts_per_page'] ) || (int) $args['posts_per_page'] < 1 ) {
			$args['posts_per_page'] = 7;
		}
		$args['posts_per_page']      = min( (int) $args['posts_per_page'], self::MAX_RESULTS );
		$args['post_status']         = 'publish';
		$args['ignore_sticky_posts'] = true;
		$args['no_found_rows']       = true;

		return $args;
	}

	/**
	 * ¿Vale la pena buscar el término en el SKU? Los SKU reales son cortos, sin
	 * espacios y casi siempre llevan algún número; "taladro" o "cinta de embalar"
	 * no pueden coincidir y solo harían escanear wp_postmeta.
	 */
	public static function looks_like_sku( $term ) {
		$term = trim( (string) $term );

		if ( '' === $term || strlen( $term ) > 40 || preg_match( '/\s/', $term ) || ! preg_match( '/[0-9]/', $term ) ) {
			return false;
		}

		return (bool) apply_filters( 'tedotamos_search_term_looks_like_sku', true, $term );
	}

	/**
	 * Para la página de resultados: respeta la opción; si está apagada, busca siempre.
	 */
	public static function should_search_sku( $term ) {
		return ! self::enabled( 'search_sku_heuristic' ) || self::looks_like_sku( $term );
	}
}
