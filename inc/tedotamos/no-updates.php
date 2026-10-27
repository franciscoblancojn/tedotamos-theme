<?php
/**
 * TeDotamos: evita que WordPress (u otro servicio) ofrezca "actualizar" este tema.
 *
 * Es una copia independiente de Martfury: una actualización lo reemplazaría
 * por el tema original y se perderían nuestros cambios.
 * Complementa la cabecera "Update URI: false" de style.css.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Carpeta (slug) del tema, por si algún día se renombra.
 *
 * @return string
 */
function tedotamos_theme_slug() {
	return basename( get_template_directory() );
}

/**
 * No envía este tema en la consulta de actualizaciones a api.wordpress.org
 * (evita choques si existe un tema público con el mismo slug).
 */
function tedotamos_exclude_from_update_check( $args, $url ) {
	if ( false === strpos( $url, '//api.wordpress.org/themes/update-check/' ) || empty( $args['body']['themes'] ) ) {
		return $args;
	}

	$themes = json_decode( $args['body']['themes'], true );
	if ( is_array( $themes ) ) {
		$slug = tedotamos_theme_slug();
		unset( $themes['themes'][ $slug ] );
		$args['body']['themes'] = wp_json_encode( $themes );
	}

	return $args;
}
add_filter( 'http_request_args', 'tedotamos_exclude_from_update_check', 10, 2 );

/**
 * Elimina cualquier actualización pendiente que algún servicio haya registrado para este tema.
 */
function tedotamos_remove_update_notice( $transient ) {
	$slug = tedotamos_theme_slug();
	if ( is_object( $transient ) && isset( $transient->response[ $slug ] ) ) {
		unset( $transient->response[ $slug ] );
	}

	return $transient;
}
add_filter( 'site_transient_update_themes', 'tedotamos_remove_update_notice' );
