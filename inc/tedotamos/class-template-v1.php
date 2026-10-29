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

	/** Plantilla de página "Categorías (TeDotamos V1)" (templates/v1/categories.php). */
	const CATEGORIES_TEMPLATE = 'tedotamos-categorias.php';

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
			'WhatsApp'          => array(
				'quote_whatsapp' => array( 'type' => 'phone', 'label' => 'Número (con indicativo, solo números)', 'default' => '573212151598', 'help' => 'Lo usan todos los botones de WhatsApp de la portada. Cada botón tiene su propio mensaje, en su sección.' ),
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
			'Barra de menú'     => array(
				'nav_enabled' => array( 'type' => 'checkbox', 'label' => 'Usar esta barra en lugar del menú y el "Header Bar" de Martfury', 'default' => 1 ),
				'nav_hotline' => array( 'type' => 'text', 'label' => 'Teléfono', 'default' => '(+57) 321 2151598', 'help' => 'Vacío = no se muestra.' ),
				'nav_items'   => array(
					'type'    => 'lines',
					'label'   => 'Enlaces',
					'help'    => 'Una línea por enlace: <code>texto|enlace</code>. Enlaces especiales: <code>{ofertas}</code> (categoría Ofertas), <code>{marcas}</code> (sección de marcas de la portada), <code>{categorias}</code> (página de categorías).',
					'default' => "Ofertas|{ofertas}\nMarcas|{marcas}",
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
				'hero_btn1_text' => array( 'type' => 'text', 'label' => 'Botón 1: texto', 'default' => 'Ir a la tienda' ),
				'hero_btn1_url'  => array( 'type' => 'url', 'label' => 'Botón 1: enlace', 'default' => '', 'help' => 'Vacío = página de categorías.' ),
				'hero_btn2_text' => array( 'type' => 'text', 'label' => 'Botón 2: texto', 'default' => 'Comprar para empresa' ),
				'hero_btn2_url'  => array( 'type' => 'url', 'label' => 'Botón 2: enlace', 'default' => '', 'help' => 'Vacío = WhatsApp con el mensaje de abajo.' ),
				'hero_wa_text'   => array( 'type' => 'textarea', 'label' => 'Botón 2: mensaje de WhatsApp', 'default' => 'Hola, quiero comprar para mi empresa. ¿Me pueden asesorar con una cotización?' ),
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
				'volume_headers'   => array( 'type' => 'text', 'label' => 'Encabezados de la tabla', 'default' => 'Descuentos por cantidad|Descuento', 'help' => 'Separados por <code>|</code>; las filas deben tener las mismas columnas.' ),
				'volume_rows'      => array(
					'type'    => 'lines',
					'label'   => 'Filas',
					'help'    => 'Una fila por línea, columnas separadas por <code>|</code>. Un enlace se escribe <code>[texto](url)</code>; con <code>[texto](whatsapp)</code> abre WhatsApp con el mensaje de abajo. ⚠️ Verificar que coincida con las reglas reales de descuento.',
					'default' => "1 a 5 unidades|30 %\n6 a 48 unidades|35 %\n49 a 200 unidades|40 %\nMás de 200 unidades|[Contáctanos](whatsapp)",
				),
				'volume_note'      => array( 'type' => 'text', 'label' => 'Nota bajo la tabla', 'default' => 'Categoría Ofertas: 4 % de descuento adicional.' ),
				'volume_wa_text'   => array( 'type' => 'textarea', 'label' => 'Mensaje de WhatsApp', 'default' => 'Hola, quiero comprar más de 200 unidades. ¿Me pueden dar una cotización?' ),
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
				'categories_enabled'  => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'categories_title'    => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Categorías principales' ),
				'categories_selected' => array( 'type' => 'terms', 'taxonomy' => 'product_cat', 'label' => 'Categorías a mostrar', 'default' => array(), 'help' => 'Ninguna marcada = todas las categorías principales, en su orden.' ),
				'categories_count'    => array( 'type' => 'number', 'label' => 'Máximo', 'default' => 0, 'min' => 0, 'max' => 50, 'help' => '0 = sin límite.' ),
			),
			'Portada · Ofertas' => array(
				'offers_enabled'  => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'offers_title'    => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Ofertas' ),
				'offers_category' => array( 'type' => 'term', 'taxonomy' => 'product_cat', 'label' => 'Categoría de ofertas', 'default' => 'ofertas', 'help' => 'Si la categoría no existe o está vacía, la sección no se muestra.' ),
				'offers_count'    => array( 'type' => 'number', 'label' => 'Cantidad', 'default' => 8, 'min' => 1, 'max' => 24 ),
			),
			'Portada · Productos' => array(
				'products_enabled' => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'products_title'   => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Productos destacados' ),
				'products_source'  => array(
					'type'    => 'select',
					'label'   => 'Productos',
					'default' => 'best_selling',
					'choices' => array(
						'best_selling' => 'Más vendidos',
						'featured'     => 'Destacados (estrella en WooCommerce)',
						'sale'         => 'En oferta',
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
				'quote_btn_text' => array( 'type' => 'text', 'label' => 'Botón de WhatsApp: texto', 'default' => 'Cotizar por WhatsApp' ),
				'quote_wa_text'  => array( 'type' => 'textarea', 'label' => 'Botón de WhatsApp: mensaje', 'default' => 'Hola, quiero una cotización. ¿Me ayudan a armar mi pedido?' ),
				'quote_bot'      => array( 'type' => 'checkbox', 'label' => 'Mostrar el bot animado', 'default' => 1 ),
			),
			'Portada · Marcas'  => array(
				'brands_enabled' => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'brands_title'   => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Nuestras marcas y fabricantes' ),
				'brands_count'   => array( 'type' => 'number', 'label' => 'Cantidad', 'default' => 10, 'min' => 1, 'max' => 30 ),
			),
			'Portada · Cotización personalizada' => array(
				'cta_enabled'   => array( 'type' => 'checkbox', 'label' => 'Mostrar', 'default' => 1 ),
				'cta_title'     => array( 'type' => 'text', 'label' => 'Título', 'default' => 'Cotización personalizada' ),
				'cta_text'      => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'Te asesoramos y te damos la mejor solución para tu empresa o negocio.' ),
				'cta_btn_text'  => array( 'type' => 'text', 'label' => 'Botón: texto', 'default' => 'Cotizar por WhatsApp' ),
				'cta_btn_url'   => array( 'type' => 'url', 'label' => 'Botón: enlace', 'default' => '', 'help' => 'Vacío = WhatsApp con el mensaje de abajo.' ),
				'cta_wa_text'   => array( 'type' => 'textarea', 'label' => 'Botón: mensaje de WhatsApp', 'default' => 'Hola, quiero una cotización personalizada para mi empresa.' ),
				'cta_features'  => array( 'type' => 'lines', 'label' => 'Características', 'help' => 'Una por línea: <code>icono|texto</code>.', 'default' => "headset|Atención personalizada\nshield|Asesoría técnica\ncart|Precios especiales por volumen" ),
			),
		);
	}

	/**
	 * Valores por defecto de versiones anteriores. Si lo guardado sigue siendo el valor viejo
	 * (nadie lo editó), se usa el nuevo por defecto; lo que el usuario cambió se respeta.
	 */
	const OLD_DEFAULTS = array(
		'hero_btn1_text'  => array( 'Comprar ahora' ),
		'volume_rows'     => array( "1 a 5|15 %|Compra directa|Medios habilitados\n6 a 24|20 %|Compra directa|Medios habilitados\n25 a 60|35 %|Cotización revisada|Transferencia anticipada\n61 o más|40 %|Cotización autorizada|Transferencia anticipada" ),
		'categories_count' => array( 6 ),
		'products_source' => array( 'featured' ),
		'cta_title'       => array( '¿Necesitas una cotización?' ),
		'cta_btn_text'    => array( 'Solicitar cotización' ),
		'quote_whatsapp'  => array( '' ),
	);

	// ------------------------------------------------------------------ opciones

	public static function fields() {
		return array_merge( ...array_values( self::schema() ) );
	}

	public static function get( $key ) {
		static $values = null;
		if ( null === $values ) {
			$defaults = wp_list_pluck( self::fields(), 'default' );
			$saved    = (array) get_option( self::OPTION, array() );
			foreach ( self::OLD_DEFAULTS as $old_key => $old_values ) {
				if ( array_key_exists( $old_key, $saved ) && in_array( $saved[ $old_key ], $old_values, true ) ) {
					unset( $saved[ $old_key ] );
				}
			}
			// Campo reemplazado: slugs escritos a mano → casillas de categorías.
			if ( ! empty( $saved['categories_slugs'] ) && empty( $saved['categories_selected'] ) ) {
				$saved['categories_selected'] = array_filter( array_map( 'trim', explode( ',', $saved['categories_slugs'] ) ) );
			}
			$values = wp_parse_args( $saved, $defaults );
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
				case 'terms':
					$clean[ $key ] = array_values( array_filter( array_map( 'sanitize_title', (array) $value ) ) );
					break;
				case 'term':
					$clean[ $key ] = sanitize_title( $value );
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
		if ( self::get( 'nav_enabled' ) ) {
			add_action( 'tedotamos_header_nav', array( __CLASS__, 'render_nav' ) );
		}
	}

	/**
	 * Lo que debe existir aunque V1 no esté activa: la plantilla de página de categorías
	 * (para poder crearla antes de cambiar de plantilla) y los botones de configuración inicial.
	 */
	public static function init_always() {
		add_filter( 'theme_page_templates', array( __CLASS__, 'register_page_template' ) );
		add_action( 'admin_post_tedotamos_v1_setup', array( __CLASS__, 'handle_setup' ) );
	}

	public static function register_page_template( $templates ) {
		$templates[ self::CATEGORIES_TEMPLATE ] = 'Categorías (TeDotamos V1)';

		return $templates;
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
		} elseif ( is_page() && self::CATEGORIES_TEMPLATE === get_page_template_slug() ) {
			$classes[] = 'tdt-v1-page';
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
		$v1 = '';
		if ( self::is_v1_front_page() ) {
			$v1 = get_template_directory() . '/templates/v1/front-page.php';
		} elseif ( is_page() && self::CATEGORIES_TEMPLATE === get_page_template_slug() ) {
			$v1 = get_template_directory() . '/templates/v1/categories.php';
		}

		return $v1 && file_exists( $v1 ) ? $v1 : $template;
	}

	/**
	 * Barra de menú de V1 (reemplaza el menú principal y el "Header Bar" de Martfury).
	 */
	public static function render_nav() {
		echo '<nav class="tdt-nav" aria-label="Menú principal"><ul>';
		$hotline = self::get( 'nav_hotline' );
		if ( $hotline ) {
			printf(
				'<li class="tdt-nav__hotline"><a href="tel:%s">%s<span>%s</span></a></li>',
				esc_attr( preg_replace( '/[^\d+]/', '', $hotline ) ),
				self::icon( 'headset' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $hotline )
			);
		}
		foreach ( self::lines( 'nav_items', 2 ) as $item ) {
			$url = self::resolve_link( $item[1] );
			if ( $url ) {
				printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $item[0] ) );
			}
		}
		echo '</ul></nav>';
	}

	/**
	 * Enlaces especiales: {ofertas}, {marcas}, {categorias}, {tienda}.
	 */
	public static function resolve_link( $url ) {
		switch ( trim( $url ) ) {
			case '{ofertas}':
				return self::offers_url();
			case '{marcas}':
				return home_url( '/#tdt-brands' );
			case '{categorias}':
			case '{tienda}':
				return self::categories_url();
		}

		return $url;
	}

	// ------------------------------------------------------------------ datos de la portada

	public static function shop_url() {
		return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	}

	/**
	 * Página que usa la plantilla "Categorías (TeDotamos V1)"; si no existe, la tienda.
	 */
	public static function categories_page() {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => self::CATEGORIES_TEMPLATE, // phpcs:ignore WordPress.DB.SlowDBQuery
				'fields'         => 'ids',
			)
		);

		return $pages ? (int) $pages[0] : 0;
	}

	public static function categories_url() {
		$page = self::categories_page();

		return $page ? get_permalink( $page ) : self::shop_url();
	}

	/**
	 * @return WP_Term|null
	 */
	public static function offers_term() {
		$slug = self::get( 'offers_category' );
		$term = $slug ? get_term_by( 'slug', $slug, 'product_cat' ) : false;

		return $term instanceof WP_Term ? $term : null;
	}

	public static function offers_url() {
		$term = self::offers_term();
		$link = $term ? get_term_link( $term ) : '';

		return $link && ! is_wp_error( $link ) ? $link : self::categories_url();
	}

	/**
	 * @return WC_Product[]
	 */
	public static function offers_products() {
		$term = self::offers_term();
		if ( ! $term || ! function_exists( 'wc_get_products' ) ) {
			return array();
		}

		return wc_get_products(
			array(
				'status'     => 'publish',
				'visibility' => 'catalog',
				'category'   => array( $term->slug ),
				'limit'      => (int) self::get( 'offers_count' ),
				'orderby'    => 'date',
				'order'      => 'DESC',
			)
		);
	}

	/**
	 * Enlace de WhatsApp con el mensaje de una sección (campo *_wa_text).
	 */
	public static function wa( $text_key ) {
		return self::whatsapp_url( (string) self::get( $text_key ) );
	}

	/**
	 * Celda de tabla: texto o enlace con formato [texto](url); "whatsapp" usa el mensaje dado.
	 */
	public static function cell( $value, $wa_key ) {
		if ( preg_match( '/^\[(.+)\]\((.+)\)$/', trim( $value ), $m ) ) {
			$url = 'whatsapp' === $m[2] ? self::wa( $wa_key ) : self::resolve_link( $m[2] );
			if ( $url ) {
				return '<a class="tdt-cell-link" href="' . esc_url( $url ) . '"' . ( 0 === strpos( $url, 'https://wa.me/' ) ? ' target="_blank" rel="noopener"' : '' ) . '>' . esc_html( $m[1] ) . '</a>';
			}
			$value = $m[1];
		}

		return esc_html( $value );
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
		$count = (int) self::get( 'categories_count' ); // 0 = todas
		$slugs = array_filter( (array) self::get( 'categories_selected' ) );
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

	// ------------------------------------------------------------------ bloques reutilizables

	/**
	 * Tarjetas de categorías (portada y página de categorías).
	 *
	 * @param WP_Term[] $categories
	 * @param bool      $with_children Lista de subcategorías debajo de cada tarjeta.
	 */
	public static function category_cards( $categories, $with_children = false ) {
		ob_start();
		echo '<ul class="tdt-cats">';
		foreach ( $categories as $cat ) {
			$thumb_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$children = $with_children ? get_terms( array( 'taxonomy' => 'product_cat', 'parent' => $cat->term_id, 'hide_empty' => true, 'menu_order' => 'ASC' ) ) : array();
			?>
			<li>
				<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
					<span class="tdt-cats__img">
						<?php
						echo $thumb_id
							? wp_get_attachment_image( $thumb_id, 'woocommerce_thumbnail', false, array( 'alt' => $cat->name ) )
							: wc_placeholder_img( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</span>
					<span class="tdt-cats__body">
						<strong><?php echo esc_html( $cat->name ); ?></strong>
						<?php if ( $cat->description ) : ?>
							<span><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $cat->description ), 12 ) ); ?></span>
						<?php elseif ( $with_children ) : ?>
							<span><?php echo (int) $cat->count; ?> productos</span>
						<?php endif; ?>
						<?php echo self::icon( 'arrow', 'tdt-icon tdt-cats__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
				</a>
				<?php if ( $children && ! is_wp_error( $children ) ) : ?>
					<ul class="tdt-cats__children">
						<?php foreach ( $children as $child ) : ?>
							<li><a href="<?php echo esc_url( get_term_link( $child ) ); ?>"><?php echo esc_html( $child->name ); ?> <span>(<?php echo (int) $child->count; ?>)</span></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
			<?php
		}
		echo '</ul>';

		return ob_get_clean();
	}

	/**
	 * Tarjetas de producto de la portada.
	 *
	 * @param WC_Product[] $products
	 * @param bool         $discount Mostrar el % de descuento en la etiqueta.
	 */
	public static function product_cards( $products, $discount = false ) {
		ob_start();
		echo '<ul class="tdt-products">';
		foreach ( $products as $product ) {
			$badge = '';
			if ( $product->is_on_sale() ) {
				$badge = 'Oferta';
				$regular = (float) $product->get_regular_price();
				$sale    = (float) $product->get_sale_price();
				if ( $discount && $regular > 0 && $sale > 0 ) {
					$badge = '-' . round( ( 1 - $sale / $regular ) * 100 ) . ' %';
				}
			}
			?>
			<li class="tdt-product">
				<?php if ( $badge ) : ?>
					<span class="tdt-badge"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
				<a class="tdt-product__img" href="<?php echo esc_url( $product->get_permalink() ); ?>">
					<?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
				<h3 class="tdt-product__title"><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
				<?php if ( $product->get_sku() ) : ?>
					<span class="tdt-product__sku">Ref. <?php echo esc_html( $product->get_sku() ); ?></span>
				<?php endif; ?>
				<span class="tdt-product__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				<a class="tdt-btn tdt-btn--primary tdt-btn--block" href="<?php echo esc_url( $product->get_permalink() ); ?>">
					Ver producto <?php echo self::icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
			<?php
		}
		echo '</ul>';

		return ob_get_clean();
	}

	/**
	 * Bot cotizando (SVG animado con CSS: .tdt-bot* en css/templates/v1.css).
	 */
	public static function bot_svg() {
		return '<svg class="tdt-bot" viewBox="0 0 240 210" role="img" aria-label="Asistente preparando una cotización">
	<ellipse class="tdt-bot__shadow" cx="92" cy="198" rx="54" ry="7"/>
	<g class="tdt-bot__bubble">
		<path d="M14 18h66a10 10 0 0 1 10 10v22a10 10 0 0 1-10 10H40l-14 12v-12H14A10 10 0 0 1 4 50V28a10 10 0 0 1 10-10z"/>
		<circle class="tdt-bot__dot" cx="30" cy="39" r="5"/>
		<circle class="tdt-bot__dot" cx="47" cy="39" r="5"/>
		<circle class="tdt-bot__dot" cx="64" cy="39" r="5"/>
	</g>
	<g class="tdt-bot__body">
		<line class="tdt-bot__antenna" x1="92" y1="58" x2="92" y2="74"/>
		<circle class="tdt-bot__led" cx="92" cy="54" r="6"/>
		<rect class="tdt-bot__head" x="52" y="74" width="80" height="62" rx="18"/>
		<rect class="tdt-bot__face" x="62" y="85" width="60" height="40" rx="12"/>
		<rect class="tdt-bot__eye" x="76" y="96" width="9" height="14" rx="4.5"/>
		<rect class="tdt-bot__eye" x="99" y="96" width="9" height="14" rx="4.5"/>
		<path class="tdt-bot__mouth" d="M84 116q8 5 16 0"/>
		<rect class="tdt-bot__torso" x="64" y="140" width="56" height="46" rx="14"/>
		<circle class="tdt-bot__core" cx="92" cy="162" r="7"/>
		<path class="tdt-bot__arm" d="M120 156q18 -2 30 -14"/>
	</g>
	<g class="tdt-bot__doc">
		<rect class="tdt-bot__paper" x="146" y="88" width="78" height="100" rx="8"/>
		<rect class="tdt-bot__clip" x="170" y="82" width="30" height="12" rx="4"/>
		<line class="tdt-bot__line" x1="158" y1="112" x2="212" y2="112"/>
		<line class="tdt-bot__line" x1="158" y1="126" x2="204" y2="126"/>
		<line class="tdt-bot__line" x1="158" y1="140" x2="210" y2="140"/>
		<line class="tdt-bot__line" x1="158" y1="154" x2="192" y2="154"/>
		<path class="tdt-bot__check" d="M190 170l7 7 14 -15"/>
	</g>
</svg>';
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
			'whatsapp' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M9.2 8.6c.3-.4.8-.4 1.1 0l.8 1.4c.2.3.1.7-.1 1l-.5.5a6 6 0 0 0 2.5 2.5l.5-.5c.3-.3.7-.3 1-.1l1.4.8c.4.3.4.8 0 1.1l-.6.6c-.5.5-1.3.7-2 .4a9 9 0 0 1-4.7-4.7c-.3-.7-.1-1.5.4-2z"/>',
		);
		if ( ! isset( $paths[ $name ] ) ) {
			$name = 'check';
		}

		return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[ $name ] . '</svg>';
	}

	public static function icon_names() {
		return 'truck, shield, building, card, credit, percent, headset, user, cart, quote, check, tag, lock, chat, whatsapp, arrow';
	}

	// ------------------------------------------------------------------ admin

	/**
	 * Botones de configuración inicial: crear la página de categorías y la categoría Ofertas.
	 */
	public static function handle_setup() {
		if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'manage_product_terms' ) ) {
			wp_die( 'No tienes permisos para esta acción.', 403 );
		}
		check_admin_referer( 'tedotamos_v1_setup' );

		$do      = isset( $_POST['do'] ) ? sanitize_key( $_POST['do'] ) : '';
		$message = '';
		$ok      = true;
		if ( 'categories_page' === $do && ! self::categories_page() ) {
			$page_id = wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_title'  => 'Categorías',
					'post_name'   => 'categorias',
				),
				true
			);
			$ok      = ! is_wp_error( $page_id );
			if ( $ok ) {
				update_post_meta( $page_id, '_wp_page_template', self::CATEGORIES_TEMPLATE );
			}
			$message = $ok ? 'Se creó la página "Categorías": ' . get_permalink( $page_id ) : $page_id->get_error_message();
		} elseif ( 'offers_category' === $do && ! self::offers_term() ) {
			$slug    = self::get( 'offers_category' ) ? self::get( 'offers_category' ) : 'ofertas';
			$term    = wp_insert_term( 'Ofertas', 'product_cat', array( 'slug' => $slug ) );
			$ok      = ! is_wp_error( $term );
			$message = $ok ? 'Se creó la categoría "Ofertas". Agrégale productos desde Productos → editar → Categorías.' : $term->get_error_message();
		}
		if ( $message ) {
			set_transient( 'tedotamos_admin_message', array( 'text' => $message, 'ok' => $ok ), 60 );
			TeDotamos_Templates::clear_caches();
		}
		wp_safe_redirect( TeDotamos_Admin::page_url( 'template' ) );
		exit;
	}

	private static function render_setup() {
		$page  = self::categories_page();
		$offer = self::offers_term();
		$rows  = array(
			array(
				'label'  => 'Página de categorías',
				'ok'     => (bool) $page,
				'detail' => $page ? '<a href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">' . esc_html( get_permalink( $page ) ) . '</a>' : 'No existe. Los botones "Ir a la tienda" llevan a la tienda mientras tanto.',
				'do'     => 'categories_page',
				'button' => 'Crear página "Categorías"',
			),
			array(
				'label'  => 'Categoría de ofertas',
				'ok'     => (bool) $offer,
				'detail' => $offer ? esc_html( $offer->name ) . ' (' . (int) $offer->count . ' productos)' : 'No existe la categoría "' . esc_html( self::get( 'offers_category' ) ) . '".',
				'do'     => 'offers_category',
				'button' => 'Crear categoría "Ofertas"',
			),
		);
		?>
		<h2 class="title">Configuración inicial</h2>
		<table class="widefat striped" style="max-width:900px">
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<th style="width:200px"><?php echo esc_html( $row['label'] ); ?></th>
						<td><?php echo $row['ok'] ? '✅ ' : '⚠️ '; ?><?php echo wp_kses_post( $row['detail'] ); ?></td>
						<td style="width:240px">
							<?php if ( ! $row['ok'] ) : ?>
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="tedotamos_v1_setup">
									<input type="hidden" name="do" value="<?php echo esc_attr( $row['do'] ); ?>">
									<?php wp_nonce_field( 'tedotamos_v1_setup' ); ?>
									<?php submit_button( $row['button'], 'secondary small', 'submit', false ); ?>
								</form>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	private static function render_terms_field( $id, $field, $f, $value ) {
		$terms = get_terms( array( 'taxonomy' => $f['taxonomy'], 'hide_empty' => false, 'orderby' => 'name' ) );
		if ( is_wp_error( $terms ) || ! $terms ) {
			echo '<em>No hay categorías.</em>';
			return;
		}
		$by_parent = array();
		foreach ( $terms as $term ) {
			$by_parent[ $term->parent ][] = $term;
		}
		$walk = function ( $parent, $depth ) use ( &$walk, $by_parent, $f, $field, $value ) {
			foreach ( isset( $by_parent[ $parent ] ) ? $by_parent[ $parent ] : array() as $term ) {
				if ( 'term' === $f['type'] ) {
					printf( '<option value="%s" %s>%s%s (%d)</option>', esc_attr( $term->slug ), selected( $value, $term->slug, false ), esc_html( str_repeat( '— ', $depth ) ), esc_html( $term->name ), (int) $term->count );
				} else {
					printf(
						'<label style="display:block;margin-left:%dpx"><input type="checkbox" name="%s[]" value="%s" %s> %s <span class="description">(%d)</span></label>',
						(int) $depth * 18,
						esc_attr( $field ),
						esc_attr( $term->slug ),
						checked( in_array( $term->slug, (array) $value, true ), true, false ),
						esc_html( $term->name ),
						(int) $term->count
					);
				}
				$walk( $term->term_id, $depth + 1 );
			}
		};
		if ( 'term' === $f['type'] ) {
			printf( '<select id="%s" name="%s"><option value="">— Ninguna —</option>', esc_attr( $id ), esc_attr( $field ) );
			$walk( 0, 0 );
			echo '</select>';
		} else {
			echo '<div style="max-height:240px;overflow:auto;padding:8px 10px;border:1px solid #dcdcde;background:#fff;max-width:500px">';
			$walk( 0, 0 );
			echo '</div>';
		}
	}

	public static function render_settings() {
		wp_enqueue_media();
		$name = self::OPTION;
		self::render_setup();
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
									case 'terms':
									case 'term':
										self::render_terms_field( $id, $field, $f, $value );
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
