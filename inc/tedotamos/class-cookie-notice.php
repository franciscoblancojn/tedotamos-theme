<?php
/**
 * TeDotamos: aviso de cookies (plantilla V1).
 *
 * Barra fija abajo con título, descripción, botón "Aceptar" y botón "Ver política de cookies"
 * (abre la página en otra pestaña). Al aceptar se guarda la cookie tdt_cookies_ok (1 año) y no
 * vuelve a salir.
 *
 * Caché: LiteSpeed guarda la misma página para todos, así que el HTML del aviso sale siempre
 * oculto (hidden) y es el navegador el que decide si mostrarlo. El script va en línea y marcado
 * data-no-optimize / data-no-defer: LiteSpeed retrasa el resto del JS hasta que el visitante
 * interactúa y, sin eso, el aviso no saldría al abrir la página.
 *
 * Es un aviso informativo: no bloquea los píxeles ni las cookies de terceros antes de aceptar.
 *
 * Se activa desde TeDotamos_Template_V1::init() solo con la plantilla V1 y cookies_enabled.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TeDotamos_Cookie_Notice {

	const COOKIE        = 'tdt_cookies_ok';
	const FALLBACK_SLUG = 'politica-de-cookies';

	public static function init() {
		add_action( 'wp_footer', array( __CLASS__, 'render' ), 5 );
	}

	/**
	 * Página de la política: la elegida en Plantilla V1 → /politica-de-cookies/ → la política de
	 * privacidad de WordPress. Solo páginas publicadas. 0 = sin página (no se muestra el botón).
	 */
	public static function page_id() {
		$candidates = array( absint( TeDotamos_Template_V1::get( 'cookies_page' ) ) );
		$fallback   = get_page_by_path( self::FALLBACK_SLUG );
		if ( $fallback ) {
			$candidates[] = (int) $fallback->ID;
		}
		$candidates[] = absint( get_option( 'wp_page_for_privacy_policy', 0 ) );
		foreach ( $candidates as $id ) {
			if ( $id && 'page' === get_post_type( $id ) && 'publish' === get_post_status( $id ) ) {
				return $id;
			}
		}

		return 0;
	}

	public static function render() {
		if ( is_admin() || wp_doing_ajax() || ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) ) {
			return;
		}
		$title    = trim( (string) TeDotamos_Template_V1::get( 'cookies_title' ) );
		$text     = trim( (string) TeDotamos_Template_V1::get( 'cookies_text' ) );
		$accept   = trim( (string) TeDotamos_Template_V1::get( 'cookies_accept_text' ) );
		$policy   = trim( (string) TeDotamos_Template_V1::get( 'cookies_policy_text' ) );
		$page_id  = self::page_id();
		$accept   = '' !== $accept ? $accept : 'Aceptar';
		$title_id = 'tdt-cookies-title';
		?>
		<div class="tdt-cookies" id="tdt-cookies" role="region" aria-label="<?php echo esc_attr( '' !== $title ? $title : 'Aviso de cookies' ); ?>" hidden>
			<div class="tdt-cookies__inner">
				<div class="tdt-cookies__body">
					<?php if ( '' !== $title ) : ?>
						<p class="tdt-cookies__title" id="<?php echo esc_attr( $title_id ); ?>"><?php echo esc_html( $title ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $text ) : ?>
						<div class="tdt-cookies__text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
					<?php endif; ?>
				</div>
				<div class="tdt-cookies__actions">
					<?php if ( $page_id && '' !== $policy ) : ?>
						<a class="tdt-cookies__btn tdt-cookies__btn--ghost" href="<?php echo esc_url( get_permalink( $page_id ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $policy ); ?></a>
					<?php endif; ?>
					<button type="button" class="tdt-cookies__btn tdt-cookies__btn--accept" id="tdt-cookies-accept"><?php echo esc_html( $accept ); ?></button>
				</div>
			</div>
		</div>
		<?php
		$js = sprintf(
			'(function(){var n=%1$s,b=document.getElementById("tdt-cookies");if(!b)return;try{if(document.cookie.indexOf(n+"=1")!==-1){b.parentNode.removeChild(b);return;}}catch(e){}b.hidden=false;document.body.classList.add("tdt-cookies-open");document.getElementById("tdt-cookies-accept").addEventListener("click",function(){document.cookie=n+"=1; max-age=31536000; path=/; SameSite=Lax"+(location.protocol==="https:"?"; Secure":"");b.hidden=true;document.body.classList.remove("tdt-cookies-open");});})();',
			wp_json_encode( self::COOKIE )
		);
		wp_print_inline_script_tag(
			$js,
			array(
				'id'               => 'tdt-cookies-js',
				'data-no-optimize' => '1',
				'data-no-defer'    => '1',
				'data-no-minify'   => '1',
			)
		);
	}
}
