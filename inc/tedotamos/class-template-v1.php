<?php
/**
 * TeDotamos: plantilla V1 (diseño TeDotamos 2026).
 *
 * - Estilo propio en todo el sitio (css/templates/v1.css), con el color principal configurable.
 * - Fuerza en Martfury el header con barra de menú (layout 1), sin la barra superior ni la
 *   promoción de Martfury (V1 pinta su propia barra superior).
 * - Portada propia (templates/v1/front-page.php) en lugar de la de Elementor.
 *
 * Todo el contenido se edita en Apariencia → TeDotamos → Plantilla V1. Los campos se definen
 * en schema(): de ahí salen el formulario, los valores por defecto y la validación.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Template_V1 {

	const OPTION = 'tedotamos_v1';

	/**
	 * Campos configurables, agrupados por sección.
	 * Listas (textarea "lines"): un elemento por línea, columnas separadas por "|".
	 */
	public static function schema() {
		return array(
			'Estilo'            => array(
				'primary_color' => array( 'type' => 'color', 'label' => 'Color principal', 'default' => '#f57c00' ),
				'dark_color'    => array( 'type' => 'color', 'label' => 'Color oscuro (barra superior, footer)', 'default' => '#1b1d22' ),
			),
			'Barra superior'    => array(
				'topbar_enabled' => array( 'type' => 'checkbox', 'label' => 'Mostrar la barra superior', 'default' => 1 ),
				'topbar_items'   => array(
					'type'    => 'lines',
					'label'   => 'Elementos',
					'help'    => 'Una línea por elemento: <code>icono|texto|enlace (opcional)</code>.',
					'default' => "truck|Envíos a todo el país y el mundo|\nshield|Garantía de satisfacción|\nbuilding|Ventas corporativas|",
				),
			),
			'Portada'           => array(
				'home_enabled' => array( 'type' => 'checkbox', 'label' => 'Usar la portada V1 (reemplaza la portada de Elementor mientras V1 esté activa)', 'default' => 1 ),
			),
			'Portada · Hero'    => array(
				'hero_enabled'   => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'hero_kicker'    => array( 'type' => 'text', 'label' => 'Antetítulo', 'default' => 'Industria · Empresas · Negocios · Personas' ),
				'hero_title'     => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Todo para dotar' ),
				'hero_highlight' => array( 'type' => 'text', 'label' => 'Título (parte resaltada)', 'default' => 'y equipar tu empresa' ),
				'hero_text'      => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'Productos de calidad, marcas confiables y los mejores precios en dotación, seguridad industrial, EPP, maquinaria, herramientas, insumos y mucho más.' ),
				'hero_btn1_text' => array( 'type' => 'text', 'label' => 'Botón 1: texto', 'default' => 'Comprar ahora' ),
				'hero_btn1_url'  => array( 'type' => 'url', 'label' => 'Botón 1: enlace', 'default' => '', 'help' => 'Vacío = página de la tienda.' ),
				'hero_btn2_text' => array( 'type' => 'text', 'label' => 'Botón 2: texto', 'default' => 'Comprar para empresa' ),
				'hero_btn2_url'  => array( 'type' => 'url', 'label' => 'Botón 2: enlace', 'default' => '', 'help' => 'Vacío = sección de cotización de la portada.' ),
				'hero_image'     => array( 'type' => 'image', 'label' => 'Imagen de fondo', 'default' => '', 'help' => 'Recomendado: 1920×700 px o más, con la persona o el producto a la derecha.' ),
				'hero_side_text' => array( 'type' => 'text', 'label' => 'Frase lateral', 'default' => 'Tu seguridad y la de tu equipo empieza aquí' ),
			),
			'Portada · Descuentos por volumen' => array(
				'volume_enabled'   => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'volume_title'     => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Compras por volumen,' ),
				'volume_highlight' => array( 'type' => 'text', 'label' => 'Título (parte resaltada)', 'default' => 'mayores descuentos' ),
				'volume_b2c_title' => array( 'type' => 'text', 'label' => 'B2C: título', 'default' => 'B2C – Personas' ),
				'volume_b2c_text'  => array( 'type' => 'text', 'label' => 'B2C: texto', 'default' => 'Descuentos según cantidad' ),
				'volume_b2b_title' => array( 'type' => 'text', 'label' => 'B2B: título', 'default' => 'B2B – Empresas' ),
				'volume_b2b_text'  => array( 'type' => 'text', 'label' => 'B2B: texto', 'default' => 'Desde 30% en adelante' ),
				'volume_rows'      => array(
					'type'    => 'lines',
					'label'   => 'Tabla',
					'help'    => 'Una fila por línea: <code>cantidad|descuento|modalidad|pago</code>. ⚠️ Verificar que coincida con las reglas reales de descuento.',
					'default' => "1 a 5|15 %|Compra directa|Medios habilitados\n6 a 24|20 %|Compra directa|Medios habilitados\n25 a 60|35 %|Cotización revisada|Transferencia anticipada\n61 o más|40 %|Cotización autorizada|Transferencia anticipada",
				),
			),
			'Portada · Beneficios' => array(
				'benefits_enabled' => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'benefits_items'   => array(
					'type'    => 'lines',
					'label'   => 'Elementos',
					'help'    => 'Una línea por elemento: <code>icono|título|texto</code>.',
					'default' => "card|Medios de pago|Transferencia · Consignación · Wompi · Llave\ncredit|Créditos personales|Sistecrédito · Addi · Suma+Play · Compra ahora, paga después Bancolombia\ntruck|Envíos|A todo el país y el mundo\nshield|Garantía de satisfacción|Tu compra protegida\nbuilding|Ventas corporativas|Soluciones a la medida de tu empresa",
				),
			),
			'Portada · Categorías' => array(
				'categories_enabled' => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'categories_title'   => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Categorías principales' ),
				'categories_slugs'   => array( 'type' => 'text', 'label' => 'Categorías (slugs, separados por coma)', 'default' => '', 'help' => 'Vacío = las categorías principales de la tienda, en su orden.' ),
				'categories_count'   => array( 'type' => 'number', 'label' => 'Cantidad', 'default' => 6, 'min' => 1, 'max' => 12 ),
			),
			'Portada · Productos' => array(
				'products_enabled' => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'products_title'   => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Productos destacados' ),
				'products_source'  => array(
					'type'    => 'select',
					'label'   => 'Productos',
					'default' => 'featured',
					'choices' => array(
						'featured'     => 'Destacados (estrella en WooCommerce)',
						'sale'         => 'En oferta',
						'best_selling' => 'Más vendidos',
						'latest'       => 'Más recientes',
					),
					'help'    => 'Si no hay productos de ese tipo, se muestran los más recientes.',
				),
				'products_count'   => array( 'type' => 'number', 'label' => 'Cantidad', 'default' => 6, 'min' => 1, 'max' => 12 ),
			),
			'Portada · Cotización inteligente' => array(
				'quote_enabled'  => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'quote_kicker'   => array( 'type' => 'text', 'label' => 'Antetítulo', 'default' => 'B2B · Empresas · Cotizaciones' ),
				'quote_title'    => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Cotización inteligente' ),
				'quote_text'     => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'Dinos qué necesitas, cantidades y ciudad. Te ayudamos a armar tu pedido.' ),
				'quote_bullets'  => array( 'type' => 'lines', 'label' => 'Puntos', 'help' => 'Uno por línea.', 'default' => "Responde tus preguntas\nTe sugiere productos\nTe entrega una cotización personalizada" ),
				'quote_whatsapp' => array( 'type' => 'phone', 'label' => 'WhatsApp (con indicativo, solo números)', 'default' => '', 'help' => 'Ej.: 573001234567. Sin número, el formulario no se muestra.' ),
			),
			'Portada · Marcas'  => array(
				'brands_enabled' => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'brands_title'   => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Nuestras marcas y fabricantes' ),
				'brands_count'   => array( 'type' => 'number', 'label' => 'Cantidad', 'default' => 10, 'min' => 1, 'max' => 30 ),
			),
			'Portada · Banner de cotización' => array(
				'cta_enabled'   => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'cta_title'     => array( 'type' => 'text', 'label' => 'Título', 'default' => '¿Necesitas una cotización?' ),
				'cta_text'      => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'Te asesoramos y te damos la mejor solución para tu empresa o negocio.' ),
				'cta_btn_text'  => array( 'type' => 'text', 'label' => 'Botón: texto', 'default' => 'Solicitar cotización' ),
				'cta_btn_url'   => array( 'type' => 'url', 'label' => 'Botón: enlace', 'default' => '', 'help' => 'Vacío = WhatsApp de cotización (o la sección de cotización).' ),
				'cta_features'  => array( 'type' => 'lines', 'label' => 'Características', 'help' => 'Una por línea: <code>icono|texto</code>.', 'default' => "headset|Atención personalizada\nshield|Asesoría técnica\ncart|Precios especiales por volumen" ),
			),
		);
	}

	// ------------------------------------------------------------------ opciones

	public static function fields() {
		return array_merge( ...array_values( self::schema() ) );
	}

	public static function get( $key ) {
		static $values = null;
		if ( null === $values ) {
			$defaults = wp_list_pluck( self::fields(), 'default' );
			$values   = wp_parse_args( (array) get_option( self::OPTION, array() ), $defaults );
		}

		return isset( $values[ $key ] ) ? $values[ $key ] : null;
	}

	/**
	 * Lista de un campo "lines": array de arrays de columnas (vacías rellenadas con '').
	 */
	public static function lines( $key, $columns ) {
		$rows = array();
		foreach ( preg_split( '/\r\n|\r|\n/', (string) self::get( $key ) ) as $line ) {
			if ( '' === trim( $line ) ) {
				continue;
			}
			$rows[] = array_pad( array_map( 'trim', explode( '|', $line, $columns ) ), $columns, '' );
		}

		return $rows;
	}

	public static function sanitize( $input ) {
		$input = (array) $input;
		$clean = array();
		foreach ( self::fields() as $key => $field ) {
			$value = isset( $input[ $key ] ) ? $input[ $key ] : '';
			switch ( $field['type'] ) {
				case 'checkbox':
					$clean[ $key ] = empty( $value ) ? 0 : 1;
					break;
				case 'color':
					$clean[ $key ] = sanitize_hex_color( $value ) ? sanitize_hex_color( $value ) : $field['default'];
					break;
				case 'number':
					$clean[ $key ] = min( $field['max'], max( $field['min'], absint( $value ) ) );
					break;
				case 'url':
				case 'image':
					$clean[ $key ] = esc_url_raw( trim( $value ) );
					break;
				case 'phone':
					$clean[ $key ] = preg_replace( '/\D+/', '', $value );
					break;
				case 'select':
					$clean[ $key ] = isset( $field['choices'][ $value ] ) ? $value : $field['default'];
					break;
				case 'textarea':
				case 'lines':
					$clean[ $key ] = sanitize_textarea_field( $value );
					break;
				default:
					$clean[ $key ] = sanitize_text_field( $value );
			}
		}

		return $clean;
	}

	// ------------------------------------------------------------------ frontend

	public static function init() {
		add_filter( 'martfury_get_option', array( __CLASS__, 'override_martfury_options' ), 20, 2 );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 30 );
		add_action( 'martfury_before_header', array( __CLASS__, 'render_topbar' ), 1 );
		add_filter( 'template_include', array( __CLASS__, 'front_page_template' ), 100 );
	}

	/**
	 * Ajustes de Martfury que V1 necesita, sin tocar los guardados en el Customizer.
	 */
	public static function override_martfury_options( $value, $name ) {
		switch ( $name ) {
			case 'header_layout':
				return '1'; // header + barra de menú con "Todas las categorías"
			case 'topbar':
			case 'promotion':
			case 'custom_header_skin':
				return 0; // V1 pinta su propia barra superior y colores
			case 'custom_color_scheme':
				return 1;
			case 'custom_color':
				return self::get( 'primary_color' );
			case 'footer_skin':
				return 'light'; // el fondo oscuro lo pone v1.css
			case 'footer_newsletter':
				// No mostrar el bloque si su formulario usa un shortcode de un plugin desactivado.
				return self::newsletter_form_ok() ? $value : 0;
		}

		return $value;
	}

	private static function newsletter_form_ok() {
		remove_filter( 'martfury_get_option', array( __CLASS__, 'override_martfury_options' ), 20 );
		$form = (string) martfury_get_option( 'footer_newsletter_form' );
		add_filter( 'martfury_get_option', array( __CLASS__, 'override_martfury_options' ), 20, 2 );

		if ( preg_match_all( '/\[([a-zA-Z0-9_-]+)/', $form, $m ) ) {
			foreach ( $m[1] as $tag ) {
				if ( ! shortcode_exists( $tag ) ) {
					return false;
				}
			}
		}

		return true;
	}

	public static function body_class( $classes ) {
		$classes[] = 'tdt-tpl-v1';
		if ( self::is_v1_front_page() ) {
			$classes[] = 'tdt-v1-home';
		}

		return $classes;
	}

	public static function enqueue() {
		$file = get_template_directory() . '/css/templates/v1.css';
		wp_enqueue_style( 'tedotamos-v1-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null );
		wp_enqueue_style( 'tedotamos-v1', get_template_directory_uri() . '/css/templates/v1.css', array( 'martfury' ), filemtime( $file ) );
		wp_add_inline_style(
			'tedotamos-v1',
			sprintf(
				'body.tdt-tpl-v1{--tdt-primary:%1$s;--tdt-dark:%2$s;--mf-primary-color:%1$s;--mf-background-primary-color:%1$s;--mf-border-primary-color:%1$s;}',
				self::get( 'primary_color' ),
				self::get( 'dark_color' )
			)
		);
	}

	public static function render_topbar() {
		if ( ! self::get( 'topbar_enabled' ) ) {
			return;
		}
		$items = self::lines( 'topbar_items', 3 );
		if ( ! $items ) {
			return;
		}
		echo '<div class="tdt-topbar"><div class="tdt-container"><ul class="tdt-topbar__items">';
		foreach ( $items as $item ) {
			list( $icon, $text, $url ) = $item;
			$inner = self::icon( $icon ) . '<span>' . esc_html( $text ) . '</span>';
			echo '<li>' . ( $url ? '<a href="' . esc_url( $url ) . '">' . $inner . '</a>' : $inner ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</ul></div></div>';
	}

	public static function is_v1_front_page() {
		return self::get( 'home_enabled' ) && is_front_page() && ! is_paged();
	}

	public static function front_page_template( $template ) {
		if ( self::is_v1_front_page() ) {
			$v1 = get_template_directory() . '/templates/v1/front-page.php';
			if ( file_exists( $v1 ) ) {
				return $v1;
			}
		}

		return $template;
	}

	// ------------------------------------------------------------------ datos de la portada

	public static function shop_url() {
		return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	}

	public static function whatsapp_url( $text = '' ) {
		$phone = self::get( 'quote_whatsapp' );
		if ( ! $phone ) {
			return '';
		}

		return 'https://wa.me/' . $phone . ( $text ? '?text=' . rawurlencode( $text ) : '' );
	}

	/**
	 * @return WP_Term[]
	 */
	public static function categories() {
		$count = (int) self::get( 'categories_count' );
		$slugs = array_filter( array_map( 'trim', explode( ',', (string) self::get( 'categories_slugs' ) ) ) );
		$args  = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => $count,
		);
		if ( $slugs ) {
			$args['slug']    = $slugs;
			$args['orderby'] = 'slug__in';
		} else {
			$args['parent']     = 0;
			$args['menu_order'] = 'ASC';
			$args['exclude']    = array( (int) get_option( 'default_product_cat' ) );
		}
		$terms = get_terms( $args );

		return is_wp_error( $terms ) ? array() : $terms;
	}

	/**
	 * @return WC_Product[]
	 */
	public static function products() {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return array();
		}
		$limit = (int) self::get( 'products_count' );
		$base  = array(
			'status'     => 'publish',
			'visibility' => 'catalog',
			'limit'      => $limit,
		);
		switch ( self::get( 'products_source' ) ) {
			case 'featured':
				$products = wc_get_products( $base + array( 'featured' => true ) );
				break;
			case 'sale':
				$ids      = array_slice( wc_get_product_ids_on_sale(), 0, 50 );
				$products = $ids ? wc_get_products( $base + array( 'include' => $ids ) ) : array();
				break;
			case 'best_selling':
				$products = wc_get_products( $base + array( 'orderby' => 'meta_value_num', 'meta_key' => 'total_sales', 'order' => 'DESC' ) );
				break;
			default:
				$products = array();
		}
		if ( ! $products ) {
			$products = wc_get_products( $base + array( 'orderby' => 'date', 'order' => 'DESC' ) );
		}

		return $products;
	}

	/**
	 * Marcas de WooCommerce (taxonomía product_brand) con imagen primero.
	 *
	 * @return WP_Term[]
	 */
	public static function brands() {
		if ( ! taxonomy_exists( 'product_brand' ) ) {
			return array();
		}
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_brand',
				'hide_empty' => true,
				'orderby'    => 'count',
				'order'      => 'DESC',
				'number'     => (int) self::get( 'brands_count' ),
			)
		);
		if ( is_wp_error( $terms ) ) {
			return array();
		}
		usort(
			$terms,
			function ( $a, $b ) {
				return (int) ! get_term_meta( $a->term_id, 'thumbnail_id', true ) - (int) ! get_term_meta( $b->term_id, 'thumbnail_id', true );
			}
		);

		return $terms;
	}

	// ------------------------------------------------------------------ íconos

	/**
	 * Íconos SVG en línea (trazos estilo Lucide). Nombres disponibles: ver $paths.
	 */
	public static function icon( $name, $class = 'tdt-icon' ) {
		$paths = array(
			'truck'    => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
			'shield'   => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
			'building' => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
			'card'     => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
			'credit'   => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
			'percent'  => '<line x1="19" x2="5" y1="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
			'headset'  => '<path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"/><path d="M21 16v2a4 4 0 0 1-4 4h-5"/>',
			'user'     => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
			'cart'     => '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>',
			'quote'    => '<rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>',
			'check'    => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
			'tag'      => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r="1"/>',
			'lock'     => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
			'chat'     => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
			'arrow'    => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		);
		if ( ! isset( $paths[ $name ] ) ) {
			$name = 'check';
		}

		return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[ $name ] . '</svg>';
	}

	public static function icon_names() {
		return 'truck, shield, building, card, credit, percent, headset, user, cart, quote, check, tag, lock, chat, arrow';
	}

	// ------------------------------------------------------------------ admin

	public static function render_settings() {
		wp_enqueue_media();
		$name = self::OPTION;
		?>
		<p>Contenido y estilo de la plantilla <strong>V1</strong>. Se aplica cuando en la pestaña General está elegida la plantilla V1
			(actual: <strong><?php echo esc_html( TeDotamos_Templates::TEMPLATES[ TeDotamos_Templates::current() ] ); ?></strong>).</p>
		<p class="description">Íconos disponibles: <code><?php echo esc_html( self::icon_names() ); ?></code></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'tedotamos_v1' ); ?>
			<?php foreach ( self::schema() as $section => $fields ) : ?>
				<h2 class="title"><?php echo esc_html( $section ); ?></h2>
				<table class="form-table" role="presentation">
					<?php foreach ( $fields as $key => $f ) : ?>
						<?php
						$id    = 'tdt-v1-' . $key;
						$value = self::get( $key );
						$field = $name . '[' . $key . ']';
						?>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $f['label'] ); ?></label></th>
							<td>
								<?php
								switch ( $f['type'] ) {
									case 'checkbox':
										printf( '<input type="checkbox" id="%s" name="%s" value="1" %s>', esc_attr( $id ), esc_attr( $field ), checked( $value, 1, false ) );
										break;
									case 'textarea':
									case 'lines':
										printf( '<textarea id="%s" name="%s" rows="%d" class="large-text%s">%s</textarea>', esc_attr( $id ), esc_attr( $field ), 'lines' === $f['type'] ? 5 : 3, 'lines' === $f['type'] ? ' code' : '', esc_textarea( $value ) );
										break;
									case 'number':
										printf( '<input type="number" id="%s" name="%s" value="%d" min="%d" max="%d" class="small-text">', esc_attr( $id ), esc_attr( $field ), (int) $value, (int) $f['min'], (int) $f['max'] );
										break;
									case 'color':
										printf( '<input type="color" id="%s" name="%s" value="%s">', esc_attr( $id ), esc_attr( $field ), esc_attr( $value ) );
										break;
									case 'select':
										printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $field ) );
										foreach ( $f['choices'] as $k => $label ) {
											printf( '<option value="%s" %s>%s</option>', esc_attr( $k ), selected( $value, $k, false ), esc_html( $label ) );
										}
										echo '</select>';
										break;
									case 'image':
										printf(
											'<input type="url" id="%1$s" name="%2$s" value="%3$s" class="regular-text"> <button type="button" class="button tdt-media" data-target="%1$s">Elegir imagen</button>',
											esc_attr( $id ),
											esc_attr( $field ),
											esc_attr( $value )
										);
										break;
									default:
										printf( '<input type="%s" id="%s" name="%s" value="%s" class="regular-text">', 'url' === $f['type'] ? 'url' : 'text', esc_attr( $id ), esc_attr( $field ), esc_attr( $value ) );
								}
								if ( ! empty( $f['help'] ) ) {
									echo '<p class="description">' . wp_kses( $f['help'], array( 'code' => array() ) ) . '</p>';
								}
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
		<script>
			jQuery( function ( $ ) {
				$( '.tdt-media' ).on( 'click', function ( e ) {
					e.preventDefault();
					var target = $( '#' + $( this ).data( 'target' ) );
					var frame = wp.media( { title: 'Elegir imagen', multiple: false, library: { type: 'image' } } );
					frame.on( 'select', function () {
						target.val( frame.state().get( 'selection' ).first().toJSON().url );
					} );
					frame.open();
				} );
			} );
		</script>
		<?php
	}
}
