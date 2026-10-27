<?php
/**
 * TeDotamos: copia la configuración del Customizer de Martfury a TeDotamos.
 *
 * WordPress guarda las opciones del Customizer por carpeta de tema (opción
 * "theme_mods_<carpeta>"). Sin esta copia, al activar TeDotamos se pierden el header,
 * logo, colores, barra de promoción, ubicaciones de menús, CSS adicional, etc.
 *
 * - Automática: la primera vez que se activa TeDotamos (si nunca se migró).
 * - Manual: botón en Apariencia → TeDotamos → Migración.
 * Antes de sobrescribir se guarda una copia de lo que tenía TeDotamos (se puede restaurar).
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Migration {

	const SOURCE     = 'martfury';
	const FLAG       = 'tedotamos_mods_migrated';
	const BACKUP     = 'tedotamos_theme_mods_backup';

	public static function init() {
		// Prioridad 1: antes de que el tema y WordPress recalculen menús, widgets y caché de estilos.
		add_action( 'after_switch_theme', array( __CLASS__, 'on_activate' ), 1 );
		add_action( 'admin_post_tedotamos_import_martfury', array( __CLASS__, 'handle_import' ) );
		add_action( 'admin_post_tedotamos_restore_backup', array( __CLASS__, 'handle_restore' ) );
	}

	public static function source_mods() {
		$mods = get_option( 'theme_mods_' . self::SOURCE );

		return is_array( $mods ) ? $mods : array();
	}

	public static function status() {
		return get_option( self::FLAG );
	}

	public static function on_activate() {
		if ( self::status() || ! self::source_mods() ) {
			return;
		}
		self::import( 'activación automática' );
	}

	/**
	 * Reemplaza las opciones del Customizer de TeDotamos por las de Martfury.
	 *
	 * @param string $how Origen de la acción (para el registro).
	 * @return int|WP_Error Número de opciones copiadas.
	 */
	public static function import( $how ) {
		$source = self::source_mods();
		if ( ! $source ) {
			return new WP_Error( 'tedotamos_no_source', 'No hay configuración de Martfury (theme_mods_martfury) para copiar.' );
		}

		$target_key = 'theme_mods_' . get_stylesheet();
		update_option( self::BACKUP, array( 'saved_at' => time(), 'mods' => get_option( $target_key ) ), false );
		update_option( $target_key, $source );
		update_option(
			self::FLAG,
			array(
				'from'  => self::SOURCE,
				'at'    => time(),
				'count' => count( $source ),
				'how'   => $how,
			),
			false
		);

		self::clear_caches();

		return count( $source );
	}

	public static function restore_backup() {
		$backup = get_option( self::BACKUP );
		if ( ! is_array( $backup ) || ! array_key_exists( 'mods', $backup ) ) {
			return new WP_Error( 'tedotamos_no_backup', 'No hay copia de seguridad para restaurar.' );
		}

		$target_key = 'theme_mods_' . get_stylesheet();
		if ( false === $backup['mods'] ) {
			delete_option( $target_key );
		} else {
			update_option( $target_key, $backup['mods'] );
		}
		delete_option( self::FLAG );
		self::clear_caches();

		return true;
	}

	/**
	 * Estilos en línea del tema (transient de 1 día) y caché de página de LiteSpeed.
	 */
	private static function clear_caches() {
		if ( function_exists( 'martfury_clear_inline_styles_cache' ) ) {
			martfury_clear_inline_styles_cache();
		}
		do_action( 'litespeed_purge_all' );
	}

	public static function handle_import() {
		self::guard( 'tedotamos_import_martfury' );
		$result = self::import( 'manual' );
		self::redirect( is_wp_error( $result ) ? $result->get_error_message() : sprintf( 'Se copiaron %d opciones desde Martfury.', $result ), ! is_wp_error( $result ) );
	}

	public static function handle_restore() {
		self::guard( 'tedotamos_restore_backup' );
		$result = self::restore_backup();
		self::redirect( is_wp_error( $result ) ? $result->get_error_message() : 'Se restauró la configuración anterior de TeDotamos.', ! is_wp_error( $result ) );
	}

	private static function guard( $action ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( 'No tienes permisos para cambiar la configuración del tema.', 403 );
		}
		check_admin_referer( $action );
	}

	private static function redirect( $message, $ok ) {
		set_transient( 'tedotamos_admin_message', array( 'text' => $message, 'ok' => $ok ), 60 );
		wp_safe_redirect( TeDotamos_Admin::page_url( 'migration' ) );
		exit;
	}
}
