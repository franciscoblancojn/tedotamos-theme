<?php
/**
 * TeDotamos: página "Apariencia → TeDotamos" para gestionar actualizaciones del tema
 * y configuraciones generales.
 *
 * Nuevas opciones: añadirlas a DEFAULTS, a sanitize() y a render_general_tab().
 * Leerlas con TeDotamos_Admin::get_option( 'clave' ).
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Admin {

	const PAGE   = 'tedotamos';
	const OPTION = 'tedotamos_options';

	const DEFAULTS = array(
		'cache_hours'   => 6, // cada cuánto se consulta GitHub
		'update_notice' => 1, // aviso en el escritorio cuando hay versión nueva
		'template'      => 'default', // plantilla de estilos (ver TeDotamos_Templates::TEMPLATES)
	);

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_post_tedotamos_check_updates', array( __CLASS__, 'handle_check_updates' ) );
		add_action( 'admin_notices', array( __CLASS__, 'update_notice' ) );
	}

	public static function get_option( $key ) {
		$options = wp_parse_args( (array) get_option( self::OPTION, array() ), self::DEFAULTS );

		return isset( $options[ $key ] ) ? $options[ $key ] : null;
	}

	public static function page_url( $tab = 'updates', $args = array() ) {
		return add_query_arg( array_merge( array( 'page' => self::PAGE, 'tab' => $tab ), $args ), admin_url( 'themes.php' ) );
	}

	public static function add_menu() {
		add_theme_page( 'TeDotamos', 'TeDotamos', 'edit_theme_options', self::PAGE, array( __CLASS__, 'render_page' ) );
	}

	public static function register_settings() {
		register_setting(
			'tedotamos_general',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'default'           => self::DEFAULTS,
			)
		);
		register_setting(
			'tedotamos_v1',
			TeDotamos_Template_V1::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'TeDotamos_Template_V1', 'sanitize' ),
			)
		);
	}

	public static function sanitize( $input ) {
		$input = (array) $input;

		$hours = isset( $input['cache_hours'] ) ? absint( $input['cache_hours'] ) : self::DEFAULTS['cache_hours'];
		$clean = array(
			'cache_hours'   => min( 24, max( 1, $hours ) ),
			'update_notice' => empty( $input['update_notice'] ) ? 0 : 1,
			'template'      => isset( $input['template'], TeDotamos_Templates::TEMPLATES[ $input['template'] ] ) ? $input['template'] : 'default',
		);

		// Si cambia la frecuencia, que la próxima consulta use el nuevo valor.
		if ( (int) $clean['cache_hours'] !== (int) self::get_option( 'cache_hours' ) ) {
			delete_transient( TeDotamos_Updater::CACHE_KEY );
		}

		return $clean;
	}

	public static function handle_check_updates() {
		if ( ! current_user_can( 'update_themes' ) ) {
			wp_die( esc_html__( 'No tienes permisos para actualizar temas.', 'martfury' ), 403 );
		}
		check_admin_referer( 'tedotamos_check_updates' );

		TeDotamos_Updater::force_check();

		wp_safe_redirect( self::page_url( 'updates', array( 'checked' => 1 ) ) );
		exit;
	}

	public static function update_notice() {
		if ( ! self::get_option( 'update_notice' ) || ! current_user_can( 'update_themes' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_' . self::PAGE === $screen->id ) {
			return;
		}
		$update = TeDotamos_Updater::pending_update();
		if ( ! $update ) {
			return;
		}

		printf(
			'<div class="notice notice-info"><p><strong>TeDotamos %1$s</strong> está disponible (instalada: %2$s). <a href="%3$s">Ver actualización</a></p></div>',
			esc_html( $update['new_version'] ),
			esc_html( TeDotamos_Updater::installed_version() ),
			esc_url( self::page_url() )
		);
	}

	// ------------------------------------------------------------------ vistas

	public static function render_page() {
		$tabs = array(
			'updates' => 'Actualizaciones',
			'general'   => 'General',
			'template'  => 'Plantilla V1',
			'migration' => 'Migración',
			'info'      => 'Información',
		);
		$tab  = isset( $_GET['tab'] ) && isset( $tabs[ $_GET['tab'] ] ) ? sanitize_key( $_GET['tab'] ) : 'updates'; // phpcs:ignore WordPress.Security.NonceVerification
		?>
		<div class="wrap">
			<h1>TeDotamos</h1>
			<nav class="nav-tab-wrapper">
				<?php foreach ( $tabs as $key => $label ) : ?>
					<a href="<?php echo esc_url( self::page_url( $key ) ); ?>" class="nav-tab <?php echo $key === $tab ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</nav>
			<?php
			$message = get_transient( 'tedotamos_admin_message' );
			if ( $message ) {
				delete_transient( 'tedotamos_admin_message' );
				printf( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $message['ok'] ? 'success' : 'error', esc_html( $message['text'] ) );
			}
			if ( 'general' === $tab ) {
				self::render_general_tab();
			} elseif ( 'template' === $tab ) {
				TeDotamos_Template_V1::render_settings();
			} elseif ( 'migration' === $tab ) {
				self::render_migration_tab();
			} elseif ( 'info' === $tab ) {
				self::render_info_tab();
			} else {
				self::render_updates_tab();
			}
			?>
		</div>
		<?php
	}

	private static function render_updates_tab() {
		$installed = TeDotamos_Updater::installed_version();
		$release   = TeDotamos_Updater::get_latest_release();
		$pending   = TeDotamos_Updater::pending_update();
		$can       = current_user_can( 'update_themes' );
		$date_fmt  = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		if ( isset( $_GET['checked'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			echo '<div class="notice notice-success is-dismissible"><p>Se consultó GitHub.</p></div>';
		}
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">Versión instalada</th>
				<td><strong><?php echo esc_html( $installed ); ?></strong></td>
			</tr>
			<tr>
				<th scope="row">Última versión publicada</th>
				<td>
					<?php if ( ! empty( $release['error'] ) ) : ?>
						<span style="color:#b32d2e"><?php echo esc_html( $release['error'] ); ?></span>
					<?php else : ?>
						<strong><?php echo esc_html( $release['version'] ); ?></strong>
						— <?php echo esc_html( mysql2date( $date_fmt, get_date_from_gmt( gmdate( 'Y-m-d H:i:s', strtotime( $release['published_at'] ) ) ) ) ); ?>
						— <a href="<?php echo esc_url( $release['url'] ); ?>" target="_blank" rel="noopener">ver en GitHub</a>
						<?php if ( 'zipball' === $release['package_type'] ) : ?>
							<p class="description">La release no tiene el asset <code><?php echo esc_html( TeDotamos_Updater::ASSET_NAME ); ?></code>: se instalará el código fuente del tag.</p>
						<?php endif; ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">Estado</th>
				<td>
					<?php if ( $pending ) : ?>
						<span class="dashicons dashicons-update" style="color:#dba617"></span>
						Hay una actualización disponible: <strong><?php echo esc_html( $pending['new_version'] ); ?></strong>
					<?php elseif ( ! empty( $release['version'] ) && version_compare( $release['version'], $installed, '>' ) ) : ?>
						<span class="dashicons dashicons-info" style="color:#2271b1"></span>
						Hay una versión nueva en GitHub; pulsa "Buscar actualizaciones" para que WordPress la registre.
					<?php else : ?>
						<span class="dashicons dashicons-yes-alt" style="color:#00a32a"></span>
						El tema está al día.
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">Última consulta</th>
				<td>
					<?php echo esc_html( isset( $release['checked_at'] ) ? wp_date( $date_fmt, $release['checked_at'] ) : '—' ); ?>
					<p class="description">
						Repositorio: <a href="<?php echo esc_url( 'https://github.com/' . TeDotamos_Updater::REPO ); ?>" target="_blank" rel="noopener"><?php echo esc_html( TeDotamos_Updater::REPO ); ?></a>
						· Token de GitHub: <?php echo TeDotamos_Updater::token() ? 'definido en wp-config.php' : 'no (no hace falta para repos públicos)'; ?>
					</p>
				</td>
			</tr>
		</table>

		<?php if ( $can ) : ?>
			<div style="display:flex;gap:8px;align-items:center;margin:1em 0">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline">
					<input type="hidden" name="action" value="tedotamos_check_updates">
					<?php wp_nonce_field( 'tedotamos_check_updates' ); ?>
					<?php submit_button( 'Buscar actualizaciones', 'secondary', 'submit', false ); ?>
				</form>
				<?php if ( $pending ) : ?>
					<a href="<?php echo esc_url( TeDotamos_Updater::upgrade_url() ); ?>" class="button button-primary"
						onclick="return confirm('¿Actualizar el tema TeDotamos a la versión <?php echo esc_js( $pending['new_version'] ); ?>? Se recomienda tener un backup.');">
						Actualizar a <?php echo esc_html( $pending['new_version'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<p class="description">Tu usuario no tiene permisos para actualizar temas.</p>
		<?php endif; ?>

		<?php if ( ! empty( $release['notes'] ) ) : ?>
			<h2>Notas de la versión <?php echo esc_html( $release['version'] ); ?></h2>
			<div class="card" style="max-width:none">
				<pre style="white-space:pre-wrap;font-family:inherit;margin:0"><?php echo esc_html( $release['notes'] ); ?></pre>
			</div>
		<?php endif; ?>
		<?php
	}

	private static function render_general_tab() {
		$name = self::OPTION;
		?>
		<form method="post" action="options.php">
			<?php settings_fields( 'tedotamos_general' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="tedotamos-template">Plantilla</label></th>
					<td>
						<select id="tedotamos-template" name="<?php echo esc_attr( $name ); ?>[template]">
							<?php foreach ( TeDotamos_Templates::TEMPLATES as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( TeDotamos_Templates::current(), $key ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description">Estilo del sitio. <strong>Default</strong> es el tema como está ahora. El contenido de V1 (portada, textos, colores) se edita en la pestaña <a href="<?php echo esc_url( self::page_url( 'template' ) ); ?>">Plantilla V1</a>. Al cambiar de plantilla se purga la caché.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="tedotamos-cache-hours">Consultar GitHub cada</label></th>
					<td>
						<input type="number" min="1" max="24" id="tedotamos-cache-hours" name="<?php echo esc_attr( $name ); ?>[cache_hours]" value="<?php echo esc_attr( self::get_option( 'cache_hours' ) ); ?>" class="small-text"> horas
						<p class="description">Entre 1 y 24. Sin token, GitHub permite 60 consultas por hora.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Avisos</th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[update_notice]" value="1" <?php checked( self::get_option( 'update_notice' ) ); ?>>
							Mostrar un aviso en el escritorio cuando haya una versión nueva del tema
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}

	private static function render_migration_tab() {
		$status   = TeDotamos_Migration::status();
		$source   = TeDotamos_Migration::source_mods();
		$current  = get_theme_mods();
		$backup   = get_option( TeDotamos_Migration::BACKUP );
		$date_fmt = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
		$can      = current_user_can( 'edit_theme_options' );
		?>
		<p>WordPress guarda la configuración del Customizer (header, logo, colores, barra de promoción,
			ubicación de menús, CSS adicional…) <strong>por tema</strong>. Aquí se copia la de Martfury a TeDotamos.
			La copia se hace sola la primera vez que se activa TeDotamos.</p>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">Estado</th>
				<td>
					<?php if ( $status ) : ?>
						<span class="dashicons dashicons-yes-alt" style="color:#00a32a"></span>
						Migrado el <?php echo esc_html( wp_date( $date_fmt, $status['at'] ) ); ?>
						(<?php echo esc_html( $status['count'] ); ?> opciones, <?php echo esc_html( $status['how'] ); ?>).
					<?php else : ?>
						<span class="dashicons dashicons-warning" style="color:#dba617"></span>
						Todavía no se ha copiado la configuración de Martfury.
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">Opciones</th>
				<td>Martfury: <strong><?php echo count( $source ); ?></strong> · TeDotamos: <strong><?php echo count( (array) $current ); ?></strong></td>
			</tr>
		</table>
		<?php if ( $can ) : ?>
			<div style="display:flex;gap:8px;margin:1em 0">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
					onsubmit="return confirm('Se reemplazará la configuración del Customizer de TeDotamos por la de Martfury. Se guarda una copia de la actual para poder restaurarla. ¿Continuar?');">
					<input type="hidden" name="action" value="tedotamos_import_martfury">
					<?php wp_nonce_field( 'tedotamos_import_martfury' ); ?>
					<?php submit_button( 'Copiar configuración desde Martfury', 'primary', 'submit', false, $source ? array() : array( 'disabled' => 'disabled' ) ); ?>
				</form>
				<?php if ( is_array( $backup ) ) : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
						onsubmit="return confirm('¿Restaurar la configuración que tenía TeDotamos antes de la última copia?');">
						<input type="hidden" name="action" value="tedotamos_restore_backup">
						<?php wp_nonce_field( 'tedotamos_restore_backup' ); ?>
						<?php submit_button( 'Restaurar copia anterior (' . wp_date( $date_fmt, $backup['saved_at'] ) . ')', 'secondary', 'submit', false ); ?>
					</form>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php
	}

	private static function render_info_tab() {
		$theme = wp_get_theme( TeDotamos_Updater::slug() );
		?>
		<table class="form-table" role="presentation">
			<tr><th scope="row">Tema</th><td><?php echo esc_html( $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ) ); ?></td></tr>
			<tr><th scope="row">Carpeta</th><td><code><?php echo esc_html( $theme->get_stylesheet_directory() ); ?></code></td></tr>
			<tr><th scope="row">Basado en</th><td>Martfury 3.4.4 (DrFuri). No recibe actualizaciones de Martfury.</td></tr>
			<tr><th scope="row">Update URI</th><td><code><?php echo esc_html( $theme->get( 'UpdateURI' ) ); ?></code></td></tr>
			<tr><th scope="row">Documentación</th><td><code>TEDOTAMOS.md</code> en la carpeta del tema.</td></tr>
		</table>
		<?php
	}
}
