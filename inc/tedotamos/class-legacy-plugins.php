<?php
/**
 * TeDotamos: anula plugins cuyo contenido ya está integrado en el tema.
 *
 * - Martfury Custom (1.0.37): su CSS está en css/tedotamos.css y sus templates en woocommerce/.
 *   Si sigue activo, reemplazaría los templates del tema por sus copias y cargaría el CSS dos veces.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Legacy_Plugins {

	public static function init() {
		// Los plugins cargan antes que el tema: sus hooks ya están registrados.
		if ( class_exists( 'MACU_CORE' ) ) {
			remove_action( 'wp_enqueue_scripts', array( 'MACU_CORE', 'enqueueStyles' ), 20 );
			remove_filter( 'woocommerce_locate_template', array( 'MACU_CORE', 'locateWooTemplate' ), 10 );
			remove_filter( 'template_include', array( 'MACU_CORE', 'locateThemeTemplate' ), 99 );
			add_action( 'admin_notices', array( __CLASS__, 'martfury_custom_notice' ) );
		}
	}

	public static function martfury_custom_notice() {
		$screen = get_current_screen();
		if ( ! current_user_can( 'activate_plugins' ) || ! $screen || ! in_array( $screen->id, array( 'plugins', 'appearance_page_tedotamos' ), true ) ) {
			return;
		}
		echo '<div class="notice notice-info"><p><strong>Martfury Custom</strong> ya está integrado en el tema TeDotamos y el plugin no está haciendo nada. Puedes desactivarlo y borrarlo.</p></div>';
	}
}
