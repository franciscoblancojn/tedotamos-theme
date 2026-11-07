<?php
/**
 * TeDotamos: casilla obligatoria "Acepto los términos y condiciones" en el checkout (plantilla V1).
 *
 * Usa la casilla nativa de WooCommerce (checkout/terms.php), que sale en la caja de pago, después
 * de los métodos de pago y antes del botón "Realizar el pedido". Así WooCommerce ya:
 * - la marca como obligatoria y no deja hacer el pedido sin marcarla (WC_Checkout::validate_checkout);
 * - conserva lo marcado cuando el checkout se recalcula por AJAX (checkout.js, #terms).
 *
 * Lo que cambia esta clase:
 * - la página de términos sale de Apariencia → TeDotamos → Plantilla V1 (si no, la de WooCommerce
 *   o la página /terminos-y-condiciones/);
 * - el texto de la casilla y un enlace "Ver términos y condiciones" que abre la página en otra pestaña;
 * - no se imprime el contenido de la página dentro del checkout (WooCommerce lo desplegaba en línea
 *   al hacer clic y el enlace no abría la página).
 *
 * Se activa desde TeDotamos_Template_V1::init() solo con la plantilla V1 y la opción terms_enabled.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Checkout_Terms {

	const FALLBACK_SLUG = 'terminos-y-condiciones';

	public static function init() {
		add_filter( 'woocommerce_terms_and_conditions_page_id', array( __CLASS__, 'page_id' ), 20 );
		add_filter( 'woocommerce_checkout_show_terms', '__return_true', 20 );
		add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', array( __CLASS__, 'checkbox_text' ), 20 );
		add_action( 'woocommerce_checkout_before_terms_and_conditions', array( __CLASS__, 'remove_inline_content' ) );
	}

	/**
	 * Página de términos: la elegida en Plantilla V1 → la de WooCommerce → /terminos-y-condiciones/.
	 * Solo páginas publicadas. 0 = no hay página (WooCommerce no muestra la casilla).
	 *
	 * @param int $wc_page_id Página que trae WooCommerce (Ajustes → Avanzado).
	 */
	public static function page_id( $wc_page_id = 0 ) {
		$candidates = array( absint( TeDotamos_Template_V1::get( 'terms_page' ) ), absint( $wc_page_id ) );
		$fallback   = get_page_by_path( self::FALLBACK_SLUG );
		if ( $fallback ) {
			$candidates[] = (int) $fallback->ID;
		}
		foreach ( $candidates as $id ) {
			if ( $id && 'page' === get_post_type( $id ) && 'publish' === get_post_status( $id ) ) {
				return $id;
			}
		}

		return 0;
	}

	/**
	 * "He leído y acepto los términos y condiciones · Ver términos y condiciones" (enlace en otra pestaña).
	 * WooCommerce lo imprime con wp_kses_post(), que permite target y rel.
	 */
	public static function checkbox_text() {
		$label = trim( (string) TeDotamos_Template_V1::get( 'terms_label' ) );
		$link  = trim( (string) TeDotamos_Template_V1::get( 'terms_link_text' ) );
		$id    = self::page_id( (int) get_option( 'woocommerce_terms_page_id', 0 ) );
		$html  = esc_html( '' !== $label ? $label : 'He leído y acepto los términos y condiciones' );
		if ( $id && '' !== $link ) {
			$html .= sprintf(
				' <a href="%s" class="tdt-terms-link" target="_blank" rel="noopener">%s</a>',
				esc_url( get_permalink( $id ) ),
				esc_html( $link )
			);
		}

		return $html;
	}

	public static function remove_inline_content() {
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
	}
}
