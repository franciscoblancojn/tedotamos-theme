<?php
/**
 * TeDotamos: traducción de los textos que salen en inglés en el sitio (es_CO).
 *
 * Martfury no trae traducción es_CO, y a WooCommerce y WCBoost les faltan algunas cadenas
 * (visor de fotos, reseñas, favoritos, comparar). Este filtro solo actúa cuando la cadena
 * NO tiene traducción (la traducción devuelta es igual al original), así que nunca pisa lo
 * que ya esté traducido en los .mo o en Loco Translate.
 *
 * Para añadir una: copiar el texto original exacto (con sus %s) como clave.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Translations {

	/** Dominios a los que se aplica. */
	const DOMAINS = array( 'martfury', 'woocommerce', 'wcboost-wishlist', 'wcboost-products-compare' );

	const STRINGS = array(
		// Catálogo y fichas.
		'Filter'                          => 'Filtrar',
		'Quick View'                      => 'Vista rápida',
		'Qty'                             => 'Cant.',
		'Clear'                           => 'Limpiar',
		'Reviews'                         => 'Reseñas',
		'There are no reviews yet.'       => 'Todavía no hay reseñas.',
		'Email'                           => 'Correo electrónico',
		'Price range: %1$s through %2$s'  => 'Rango de precios: de %1$s a %2$s',
		'Next Page'                       => 'Página siguiente',
		'Previous Page'                   => 'Página anterior',
		'Loading'                         => 'Cargando',
		'Loading more products'           => 'Cargando más productos…',
		'Product Pagination'              => 'Paginación de productos',
		// Visor de fotos de la ficha (PhotoSwipe).
		'Close (Esc)'                     => 'Cerrar (Esc)',
		'Share'                           => 'Compartir',
		'Toggle fullscreen'               => 'Pantalla completa',
		'Zoom in/out'                     => 'Acercar/alejar',
		'Previous (arrow left)'           => 'Anterior (flecha izquierda)',
		'Next (arrow right)'              => 'Siguiente (flecha derecha)',
		'Close'                           => 'Cerrar',
		// Favoritos y comparar (WCBoost).
		'Wishlist'                        => 'Favoritos',
		'Add to wishlist'                 => 'Añadir a favoritos',
		'View wishlist'                   => 'Ver favoritos',
		'Add %s to the wishlist'          => 'Añadir %s a favoritos',
		'The wishlist is empty.'          => 'Tu lista de favoritos está vacía.',
		'Compare'                         => 'Comparar',
		'Compare %s'                      => 'Comparar %s',
		'Compare products'                => 'Comparar productos',
		'Return to shop'                  => 'Volver a la tienda',
		// Mi cuenta.
		'Username or email address'       => 'Usuario o correo electrónico',
		'Password'                        => 'Contraseña',
		'Remember me'                     => 'Recordarme',
		'Forgot your password?'           => '¿Olvidaste tu contraseña?',
		'Register'                        => 'Registrarse',
		'Email address'                   => 'Correo electrónico',
		'A password will be sent to your email address.' => 'Te enviaremos una contraseña a tu correo.',
	);

	public static function init() {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return; // solo el sitio (y las respuestas AJAX del sitio)
		}
		add_filter( 'gettext', array( __CLASS__, 'translate' ), 20, 3 );
	}

	public static function translate( $translation, $text, $domain ) {
		if ( $translation === $text && isset( self::STRINGS[ $text ] ) && in_array( $domain, self::DOMAINS, true ) ) {
			return self::STRINGS[ $text ];
		}

		return $translation;
	}
}
