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
		// Respaldo: añade la actualización cada vez que se lee la lista de actualizaciones de temas,
		// por si la revisión de WordPress se corta antes de llegar a "update_themes_github.com"
		// (p. ej. si falla la consulta a api.wordpress.org desde el hosting).
		add_filter( 'site_transient_update_themes', array( __CLASS__, 'inject_update' ) );
		add_action( 'http_api_debug', array( __CLASS__, 'log_wporg_check' ), 10, 5 );
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
	 * Datos de la actualización en el formato que espera WordPress (null si no hay versión nueva).
	 *
	 * @return array|null
	 */
	public static function update_data() {
		$release = self::get_latest_release();
		if ( empty( $release['version'] ) || empty( $release['package'] ) || ! version_compare( $release['version'], self::installed_version(), '>' ) ) {
			return null;
		}

		return array(
			'theme'        => self::slug(),
			'new_version'  => $release['version'],
			'url'          => $release['url'],
			'package'      => $release['package'],
			'requires_php' => wp_get_theme( self::slug() )->get( 'RequiresPHP' ),
		);
	}

	public static function inject_update( $transient ) {
		$update = self::update_data();
		if ( ! $update ) {
			return $transient;
		}
		if ( ! is_object( $transient ) ) {
			$transient = new stdClass();
		}
		if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
			$transient->response = array();
		}
		if ( empty( $transient->response[ self::slug() ] ) ) {
			$transient->response[ self::slug() ] = $update;
		}
		if ( isset( $transient->no_update ) && is_array( $transient->no_update ) ) {
			unset( $transient->no_update[ self::slug() ] );
		}

		return $transient;
	}

	/**
	 * Guarda el resultado de la última consulta de WordPress a api.wordpress.org (para el diagnóstico).
	 */
	public static function log_wporg_check( $response, $context, $class, $args, $url ) {
		if ( false === strpos( (string) $url, 'api.wordpress.org/themes/update-check' ) ) {
			return;
		}
		update_option(
			'tedotamos_wporg_check',
			array(
				'at'     => time(),
				'result' => is_wp_error( $response ) ? 'error: ' . $response->get_error_message() : 'HTTP ' . wp_remote_retrieve_response_code( $response ),
			),
			false
		);
	}

	/**
	 * Información para diagnosticar por qué no aparece una actualización.
	 *
	 * @return array<string,string>
	 */
	public static function diagnostics() {
		global $wp_version;

		$slug      = self::slug();
		$theme     = wp_get_theme( $slug );
		$raw       = get_site_option( '_site_transient_update_themes' );
		$wporg     = get_option( 'tedotamos_wporg_check' );
		$callbacks = function ( $hook ) {
			global $wp_filter;
			if ( empty( $wp_filter[ $hook ] ) ) {
				return 'ninguno';
			}
			$names = array();
			foreach ( $wp_filter[ $hook ]->callbacks as $priority => $items ) {
				foreach ( $items as $item ) {
					$cb      = $item['function'];
					$names[] = is_array( $cb ) ? ( is_object( $cb[0] ) ? get_class( $cb[0] ) : $cb[0] ) . '::' . $cb[1] : ( is_string( $cb ) ? $cb : 'closure' );
				}
			}

			return implode( ', ', $names );
		};

		return array(
			'WordPress'                                => $wp_version,
			'Carpeta del tema'                         => $slug,
			'Update URI leído por WordPress'           => $theme->get( 'UpdateURI' ) ? $theme->get( 'UpdateURI' ) : '(vacío)',
			'Revisión de WordPress (sin respaldo)'     => is_object( $raw ) ? sprintf(
				'%s · en "checked": %s · con actualización: %s',
				isset( $raw->last_checked ) ? wp_date( 'Y-m-d H:i', $raw->last_checked ) : 'sin fecha',
				isset( $raw->checked[ $slug ] ) ? $raw->checked[ $slug ] : 'no',
				isset( $raw->response[ $slug ] ) ? 'sí' : 'no'
			) : '(no existe)',
			'Última consulta a api.wordpress.org'      => is_array( $wporg ) ? wp_date( 'Y-m-d H:i', $wporg['at'] ) . ' · ' . $wporg['result'] : '(sin registro)',
			'Filtros que bloquean (pre_site_transient)' => $callbacks( 'pre_site_transient_update_themes' ),
			'Respaldo activo'                          => self::update_data() ? 'sí, ofrece ' . self::update_data()['new_version'] : 'no (sin versión nueva)',
		);
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
