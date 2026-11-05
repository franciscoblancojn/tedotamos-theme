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
		);

		return 'tdts_' . md5( wp_json_encode( $parts ) );
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
