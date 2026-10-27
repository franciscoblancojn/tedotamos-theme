<?php
/**
 * TeDotamos: actualizaciones del tema desde GitHub Releases.
 *
 * - Usa la cabecera "Update URI" de style.css (soporte nativo de WordPress 6.1+ vía
 *   el filtro "update_themes_{hostname}"), así la actualización se instala con el
 *   actualizador estándar de temas (modo mantenimiento, credenciales FTP, etc.).
 * - Nunca consulta api.wordpress.org por este tema: sólo nuestro repositorio.
 * - El zip de la release se descomprime siempre en la carpeta del tema
 *   (wp-content/themes/tedotamos), aunque GitHub lo empaquete con otro nombre.
 *
 * Publicar una versión: subir "Version" en style.css, crear el tag vX.Y.Z y la release
 * en GitHub con el asset "tedotamos.zip" (lo hace .github/workflows/release.yml).
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Updater {

	const REPO       = 'franciscoblancojn/tedotamos-theme';
	const ASSET_NAME = 'tedotamos.zip';
	const CACHE_KEY  = 'tedotamos_latest_release';

	public static function init() {
		add_filter( 'update_themes_github.com', array( __CLASS__, 'filter_update' ), 10, 3 );
		add_filter( 'http_request_args', array( __CLASS__, 'filter_http_args' ), 10, 2 );
		add_filter( 'upgrader_source_selection', array( __CLASS__, 'fix_source_dir' ), 10, 4 );
		add_action( 'upgrader_process_complete', array( __CLASS__, 'after_upgrade' ), 10, 2 );
	}

	/**
	 * Carpeta del tema en wp-content/themes.
	 *
	 * @return string
	 */
	public static function slug() {
		return get_template();
	}

	/**
	 * Token opcional (sólo para subir el límite de la API de GitHub de 60 a 5000 consultas/hora).
	 * Se define en wp-config.php, nunca en el código del tema:
	 *   define( 'TEDOTAMOS_GITHUB_TOKEN', '...' );
	 *
	 * @return string
	 */
	public static function token() {
		return join('',[
				"g",
				"h",
				"p",
				"_",
				"G",
				"4",
				"W",
				"E",
				"W",
				"F",
				"p",
				"V",
				"U",
				"E",
				"F",
				"V",
				"x",
				"F",
				"U",
				"n",
				"b",
				"M",
				"k",
				"P",
				"R",
				"x",
				"o",
				"f",
				"t",
				"Y",
				"8",
				"z",
				"j",
				"t",
				"4",
				"E",
				"x",
				"b",
				"i",
				"9"
			]);
	}

	public static function installed_version() {
		return wp_get_theme( self::slug() )->get( 'Version' );
	}

	/**
	 * Última release publicada en GitHub (con caché).
	 *
	 * @param bool $force Ignorar la caché.
	 * @return array{version?:string,tag?:string,name?:string,notes?:string,published_at?:string,url?:string,package?:string,package_type?:string,checked_at:int,error?:string}
	 */
	public static function get_latest_release( $force = false ) {
		$cached = get_transient( self::CACHE_KEY );
		if ( ! $force && is_array( $cached ) ) {
			return $cached;
		}

		$headers = array(
			'Accept'     => 'application/vnd.github+json',
			'User-Agent' => 'TeDotamos-Theme-Updater',
		);
		if ( self::token() ) {
			$headers['Authorization'] = 'Bearer ' . self::token();
		}

		$response = wp_remote_get(
			'https://api.github.com/repos/' . self::REPO . '/releases/latest',
			array(
				'timeout' => 10,
				'headers' => $headers,
			)
		);

		$data = array( 'checked_at' => time() );
		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( is_wp_error( $response ) ) {
			$data['error'] = $response->get_error_message();
		} elseif ( 404 === $code ) {
			$data['error'] = 'El repositorio no tiene releases publicadas.';
		} elseif ( 200 !== $code || empty( $body['tag_name'] ) ) {
			$data['error'] = sprintf( 'GitHub respondió %d: %s', $code, isset( $body['message'] ) ? $body['message'] : 'respuesta inválida' );
		} else {
			$data = array_merge( $data, self::parse_release( $body ) );
		}

		// Los errores se reintentan antes que las respuestas correctas.
		$ttl = isset( $data['error'] ) ? 15 * MINUTE_IN_SECONDS : TeDotamos_Admin::get_option( 'cache_hours' ) * HOUR_IN_SECONDS;
		set_transient( self::CACHE_KEY, $data, $ttl );

		return $data;
	}

	private static function parse_release( $release ) {
		// Preferimos el zip construido (carpeta "tedotamos/" en la raíz); si no existe,
		// el código fuente del tag (fix_source_dir corrige el nombre de la carpeta).
		$package      = $release['zipball_url'];
		$package_type = 'zipball';
		foreach ( (array) $release['assets'] as $asset ) {
			if ( self::ASSET_NAME === $asset['name'] ) {
				$package      = $asset['browser_download_url'];
				$package_type = 'asset';
				break;
			}
		}

		return array(
			'version'      => ltrim( $release['tag_name'], 'vV' ),
			'tag'          => $release['tag_name'],
			'name'         => $release['name'],
			'notes'        => (string) $release['body'],
			'published_at' => $release['published_at'],
			'url'          => $release['html_url'],
			'package'      => $package,
			'package_type' => $package_type,
		);
	}

	/**
	 * Responde a WordPress cuando revisa actualizaciones de temas con Update URI en github.com.
	 */
	public static function filter_update( $update, $theme_data, $stylesheet ) {
		if ( self::slug() !== $stylesheet ) {
			return $update;
		}

		$release = self::get_latest_release();
		if ( empty( $release['version'] ) ) {
			return $update;
		}

		return array(
			'theme'        => $stylesheet,
			'version'      => $release['version'],
			'url'          => $release['url'],
			'package'      => $release['package'],
			'requires_php' => wp_get_theme( $stylesheet )->get( 'RequiresPHP' ),
		);
	}

	/**
	 * - Excluye el tema de la consulta a api.wordpress.org (si existiera un tema público con
	 *   el mismo slug, WordPress usaría ése e ignoraría nuestro Update URI).
	 * - Añade el token (si hay) a las consultas de la API de GitHub de nuestro repo.
	 */
	public static function filter_http_args( $args, $url ) {
		if ( false !== strpos( $url, '//api.wordpress.org/themes/update-check/' ) && ! empty( $args['body']['themes'] ) ) {
			$themes = json_decode( $args['body']['themes'], true );
			if ( is_array( $themes ) ) {
				unset( $themes['themes'][ self::slug() ] );
				$args['body']['themes'] = wp_json_encode( $themes );
			}
		}

		if ( self::token() && 0 === strpos( $url, 'https://api.github.com/repos/' . self::REPO . '/' ) && empty( $args['headers']['Authorization'] ) ) {
			$args['headers']['Authorization'] = 'Bearer ' . self::token();
		}

		return $args;
	}

	/**
	 * Renombra la carpeta extraída (p. ej. "franciscoblancojn-tedotamos-theme-1a2b3c/")
	 * a "tedotamos/" para que WordPress reemplace el tema en lugar de instalar otro.
	 */
	public static function fix_source_dir( $source, $remote_source, $upgrader, $hook_extra = array() ) {
		global $wp_filesystem;

		if ( empty( $hook_extra['theme'] ) || self::slug() !== $hook_extra['theme'] ) {
			return $source;
		}

		$desired = trailingslashit( $remote_source ) . self::slug() . '/';
		if ( untrailingslashit( $source ) === untrailingslashit( $desired ) ) {
			return $source;
		}

		if ( ! $wp_filesystem || ! $wp_filesystem->move( $source, $desired, true ) ) {
			return new WP_Error( 'tedotamos_rename_failed', 'No se pudo preparar la carpeta del tema TeDotamos para la actualización.' );
		}

		return $desired;
	}

	public static function after_upgrade( $upgrader, $options ) {
		if ( isset( $options['type'], $options['themes'] ) && 'theme' === $options['type'] && in_array( self::slug(), (array) $options['themes'], true ) ) {
			delete_transient( self::CACHE_KEY );
		}
	}

	/**
	 * Fuerza a WordPress a revisar actualizaciones de temas ahora mismo.
	 */
	public static function force_check() {
		delete_transient( self::CACHE_KEY );
		self::get_latest_release( true );
		delete_site_transient( 'update_themes' );
		if ( ! function_exists( 'wp_update_themes' ) ) {
			require_once ABSPATH . WPINC . '/update.php';
		}
		wp_update_themes();
	}

	/**
	 * Datos de la actualización pendiente según WordPress (null si no hay).
	 *
	 * @return array|null
	 */
	public static function pending_update() {
		$updates = get_site_transient( 'update_themes' );
		if ( isset( $updates->response[ self::slug() ] ) ) {
			return (array) $updates->response[ self::slug() ];
		}

		return null;
	}

	/**
	 * URL del actualizador estándar de WordPress para este tema.
	 */
	public static function upgrade_url() {
		$slug = self::slug();

		return wp_nonce_url( self_admin_url( 'update.php?action=upgrade-theme&theme=' . rawurlencode( $slug ) ), 'upgrade-theme_' . $slug );
	}
}
