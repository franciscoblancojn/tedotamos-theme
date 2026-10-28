<?php
/**
 * TeDotamos: plantillas (estilos) del tema.
 *
 * - default: el tema tal como está (Martfury + configuración del Customizer).
 * - v1:      estilo nuevo en todo el sitio + portada propia (templates/v1/front-page.php).
 *
 * La plantilla activa se elige en Apariencia → TeDotamos → General y se guarda en
 * tedotamos_options['template']. Para añadir una plantilla: registrarla en TEMPLATES, crear su
 * clase (como TeDotamos_Template_V1) y sus archivos en css/templates/ y templates/<id>/.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Templates {

	const TEMPLATES = array(
		'default' => 'Default (Martfury, como está ahora)',
		'v1'      => 'V1 (diseño TeDotamos 2026)',
	);

	public static function init() {
		if ( 'v1' === self::current() ) {
			TeDotamos_Template_V1::init();
		}

		// Los estilos en línea del tema se guardan en caché (1 día): al cambiar de plantilla
		// o su configuración hay que regenerarlos y purgar la caché de página.
		add_action( 'update_option_' . TeDotamos_Admin::OPTION, array( __CLASS__, 'maybe_clear_caches' ), 10, 2 );
		add_action( 'update_option_' . TeDotamos_Template_V1::OPTION, array( __CLASS__, 'clear_caches' ) );
	}

	public static function current() {
		$template = TeDotamos_Admin::get_option( 'template' );

		return isset( self::TEMPLATES[ $template ] ) ? $template : 'default';
	}

	public static function maybe_clear_caches( $old, $new ) {
		$old_tpl = isset( $old['template'] ) ? $old['template'] : 'default';
		$new_tpl = isset( $new['template'] ) ? $new['template'] : 'default';
		if ( $old_tpl !== $new_tpl ) {
			self::clear_caches();
		}
	}

	public static function clear_caches() {
		if ( function_exists( 'martfury_clear_inline_styles_cache' ) ) {
			martfury_clear_inline_styles_cache();
		}
		do_action( 'litespeed_purge_all' );
	}
}
