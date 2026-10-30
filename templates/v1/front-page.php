<?php
/**
 * TeDotamos: portada de la plantilla V1.
 *
 * Se usa en lugar de la portada de Elementor cuando la plantilla V1 está activa y
 * "Usar la portada V1" está marcado (TeDotamos_Template_V1::front_page_template()).
 * Contenido: Apariencia → TeDotamos → Plantilla V1.
 *
 * Los botones "a la tienda" llevan a la página de categorías; los de compra empresarial y
 * cotización abren WhatsApp con el mensaje de su sección.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$v1       = 'TeDotamos_Template_V1';
$cats_url = $v1::categories_url();
$quote_on = $v1::get( 'quote_enabled' );

/**
 * Enlace de un botón: el configurado, o WhatsApp con el mensaje de la sección,
 * o la sección de cotización si no hay número de WhatsApp.
 */
$tdt_wa_or = function ( $url_key, $wa_key ) use ( $v1, $quote_on, $cats_url ) {
	if ( $v1::get( $url_key ) ) {
		return $v1::resolve_link( $v1::get( $url_key ) );
	}
	$wa = $v1::wa( $wa_key );

	return $wa ? $wa : ( $quote_on ? '#tdt-quote' : $cats_url );
};
$tdt_target = function ( $url ) {
	return 0 === strpos( $url, 'https://wa.me/' ) ? ' target="_blank" rel="noopener"' : '';
};

get_header();
?>
<main class="tdt-home">

	<?php if ( $v1::get( 'hero_enabled' ) ) : ?>
		<?php
		$hero_image = $v1::get( 'hero_image' );
		$hero_btn1  = $v1::get( 'hero_btn1_url' ) ? $v1::resolve_link( $v1::get( 'hero_btn1_url' ) ) : $cats_url;
		$hero_btn2  = $tdt_wa_or( 'hero_btn2_url', 'hero_wa_text' );
		?>
		<section class="tdt-hero<?php echo $hero_image ? ' has-image' : ''; ?>"<?php echo $hero_image ? ' style="--tdt-hero-image:url(' . esc_url( $hero_image ) . ')"' : ''; ?>>
			<div class="tdt-container tdt-hero__inner">
				<div class="tdt-hero__content">
					<?php if ( $v1::get( 'hero_kicker' ) ) : ?>
						<p class="tdt-kicker"><?php echo esc_html( $v1::get( 'hero_kicker' ) ); ?></p>
					<?php endif; ?>
					<h1 class="tdt-hero__title">
						<?php echo esc_html( $v1::get( 'hero_title' ) ); ?>
						<span class="tdt-accent"><?php echo esc_html( $v1::get( 'hero_highlight' ) ); ?></span>
					</h1>
					<?php if ( $v1::get( 'hero_text' ) ) : ?>
						<p class="tdt-hero__text"><?php echo esc_html( $v1::get( 'hero_text' ) ); ?></p>
					<?php endif; ?>
					<div class="tdt-actions">
						<?php if ( $v1::get( 'hero_btn1_text' ) ) : ?>
							<a class="tdt-btn tdt-btn--primary" href="<?php echo esc_url( $hero_btn1 ); ?>">
								<?php echo $v1::icon( 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo esc_html( $v1::get( 'hero_btn1_text' ) ); ?>
							</a>
						<?php endif; ?>
						<?php if ( $v1::get( 'hero_btn2_text' ) ) : ?>
							<a class="tdt-btn tdt-btn--ghost" href="<?php echo esc_url( $hero_btn2 ); ?>"<?php echo $tdt_target( $hero_btn2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo $v1::icon( 'building' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo esc_html( $v1::get( 'hero_btn2_text' ) ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( $v1::get( 'hero_side_text' ) ) : ?>
					<p class="tdt-hero__side"><?php echo esc_html( $v1::get( 'hero_side_text' ) ); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$headers = array_map( 'trim', explode( '|', (string) $v1::get( 'volume_headers' ) ) );
	$rows    = $v1::lines( 'volume_rows', max( 1, count( $headers ) ) );
	?>
	<?php if ( $v1::get( 'volume_enabled' ) && $rows ) : ?>
		<section class="tdt-section tdt-volume-wrap">
			<div class="tdt-container">
				<div class="tdt-volume">
					<div class="tdt-volume__intro">
						<span class="tdt-volume__badge"><?php echo $v1::icon( 'percent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<h2>
							<?php echo esc_html( $v1::get( 'volume_title' ) ); ?>
							<span class="tdt-accent"><?php echo esc_html( $v1::get( 'volume_highlight' ) ); ?></span>
						</h2>
					</div>
					<div class="tdt-volume__detail">
						<div class="tdt-volume__segments">
							<div>
								<?php echo $v1::icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<strong><?php echo esc_html( $v1::get( 'volume_b2c_title' ) ); ?></strong>
								<span><?php echo esc_html( $v1::get( 'volume_b2c_text' ) ); ?></span>
							</div>
							<div>
								<?php echo $v1::icon( 'building' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<strong><?php echo esc_html( $v1::get( 'volume_b2b_title' ) ); ?></strong>
								<span><?php echo esc_html( $v1::get( 'volume_b2b_text' ) ); ?></span>
							</div>
						</div>
						<div class="tdt-table-scroll">
							<table class="tdt-volume__table">
								<thead>
									<tr>
										<?php foreach ( $headers as $header ) : ?>
											<th><?php echo esc_html( $header ); ?></th>
										<?php endforeach; ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $rows as $row ) : ?>
										<tr>
											<?php foreach ( $row as $i => $cell ) : ?>
												<td><?php echo 1 === $i ? '<strong>' : ''; ?><?php echo $v1::cell( $cell, 'volume_wa_text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo 1 === $i ? '</strong>' : ''; ?></td>
											<?php endforeach; ?>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<?php if ( $v1::get( 'volume_note' ) ) : ?>
							<p class="tdt-volume__note">
								<?php echo $v1::icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo esc_html( $v1::get( 'volume_note' ) ); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php $benefits = $v1::lines( 'benefits_items', 3 ); ?>
	<?php if ( $v1::get( 'benefits_enabled' ) && $benefits ) : ?>
		<section class="tdt-section tdt-benefits-wrap">
			<div class="tdt-container">
				<ul class="tdt-benefits">
					<?php foreach ( $benefits as $b ) : ?>
						<li>
							<?php echo $v1::icon( $b[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<strong><?php echo esc_html( $b[1] ); ?></strong>
							<span><?php echo esc_html( $b[2] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php $categories = $v1::get( 'categories_enabled' ) ? $v1::categories() : array(); ?>
	<?php if ( $categories ) : ?>
		<section class="tdt-section">
			<div class="tdt-container">
				<header class="tdt-section__head">
					<h2><?php echo esc_html( $v1::get( 'categories_title' ) ); ?></h2>
					<a href="<?php echo esc_url( $cats_url ); ?>">Ver todas las categorías <?php echo $v1::icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</header>
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $v1::category_cards( $categories );
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php $offers = $v1::get( 'offers_enabled' ) ? $v1::offers_products() : array(); ?>
	<?php if ( $offers ) : ?>
		<section class="tdt-section" id="tdt-offers">
			<div class="tdt-container">
				<header class="tdt-section__head">
					<h2><?php echo $v1::icon( 'tag', 'tdt-icon tdt-accent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $v1::get( 'offers_title' ) ); ?></h2>
					<a href="<?php echo esc_url( $v1::offers_url() ); ?>">Ver todos los productos <?php echo $v1::icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</header>
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $v1::product_cards( $offers, true );
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php $products = $v1::get( 'products_enabled' ) ? $v1::products() : array(); ?>
	<?php if ( $products ) : ?>
		<section class="tdt-section">
			<div class="tdt-container">
				<header class="tdt-section__head">
					<h2><?php echo esc_html( $v1::get( 'products_title' ) ); ?></h2>
					<a href="<?php echo esc_url( $cats_url ); ?>">Ver todos los productos <?php echo $v1::icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</header>
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $v1::product_cards( $products );
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $quote_on ) : ?>
		<?php $quote_wa = $v1::wa( 'quote_wa_text' ); ?>
		<section class="tdt-section" id="tdt-quote">
			<div class="tdt-container">
				<div class="tdt-quote<?php echo $v1::get( 'quote_bot' ) ? ' has-bot' : ''; ?>">
					<div class="tdt-quote__intro">
						<p class="tdt-kicker"><?php echo esc_html( $v1::get( 'quote_kicker' ) ); ?></p>
						<h2><?php echo esc_html( $v1::get( 'quote_title' ) ); ?></h2>
						<p><?php echo esc_html( $v1::get( 'quote_text' ) ); ?></p>
						<?php $bullets = $v1::lines( 'quote_bullets', 1 ); ?>
						<?php if ( $bullets ) : ?>
							<ul class="tdt-checks">
								<?php foreach ( $bullets as $bullet ) : ?>
									<li><?php echo $v1::icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $bullet[0] ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<?php if ( $quote_wa && $v1::get( 'quote_btn_text' ) ) : ?>
							<a class="tdt-btn tdt-btn--whatsapp" href="<?php echo esc_url( $quote_wa ); ?>" target="_blank" rel="noopener">
								<?php echo $v1::icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo esc_html( $v1::get( 'quote_btn_text' ) ); ?>
							</a>
						<?php endif; ?>
					</div>
					<?php if ( $v1::get( 'quote_bot' ) ) : ?>
						<div class="tdt-quote__bot">
							<?php echo $v1::bot_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
					<?php if ( $quote_wa ) : ?>
						<form class="tdt-quote__form" data-whatsapp="<?php echo esc_attr( $v1::get( 'quote_whatsapp' ) ); ?>">
							<p class="tdt-quote__form-title"><?php echo $v1::icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> Cuéntanos qué necesitas</p>
							<label>Producto o referencia<input type="text" name="producto" required></label>
							<label>Cantidad<input type="text" name="cantidad" inputmode="numeric" required></label>
							<label>Departamento de entrega
								<select name="departamento" required>
									<option value="">Selecciona…</option>
									<?php foreach ( $v1::COLOMBIA_DEPARTMENTS as $dpto ) : ?>
										<option><?php echo esc_html( $dpto ); ?></option>
									<?php endforeach; ?>
								</select>
							</label>
							<label>Ciudad de entrega<input type="text" name="ciudad" required></label>
							<button type="submit" class="tdt-btn tdt-btn--dark tdt-btn--block">Enviar por WhatsApp</button>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php $brands = $v1::get( 'brands_enabled' ) ? $v1::brand_logos() : array(); ?>
	<?php if ( $brands ) : ?>
		<section class="tdt-section" id="tdt-brands">
			<div class="tdt-container">
				<header class="tdt-section__head">
					<h2><?php echo esc_html( $v1::get( 'brands_title' ) ); ?></h2>
				</header>
				<ul class="tdt-brands">
					<?php foreach ( $brands as $brand ) : ?>
						<?php $img = '<img src="' . esc_url( $brand['image'] ) . '" alt="' . esc_attr( $brand['name'] ) . '" loading="lazy" decoding="async" height="60">'; ?>
						<li>
							<?php if ( $brand['link'] ) : ?>
								<a href="<?php echo esc_url( $brand['link'] ); ?>" title="<?php echo esc_attr( $brand['name'] ); ?>"><?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							<?php else : ?>
								<span title="<?php echo esc_attr( $brand['name'] ); ?>"><?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $v1::get( 'cta_enabled' ) ) : ?>
		<?php $cta_url = $tdt_wa_or( 'cta_btn_url', 'cta_wa_text' ); ?>
		<section class="tdt-section">
			<div class="tdt-container">
				<div class="tdt-cta">
					<span class="tdt-cta__icon"><?php echo $v1::icon( 'quote' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<div class="tdt-cta__text">
						<h2><?php echo esc_html( $v1::get( 'cta_title' ) ); ?></h2>
						<p><?php echo esc_html( $v1::get( 'cta_text' ) ); ?></p>
						<a class="tdt-btn tdt-btn--primary" href="<?php echo esc_url( $cta_url ); ?>"<?php echo $tdt_target( $cta_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php echo 0 === strpos( $cta_url, 'https://wa.me/' ) ? $v1::icon( 'whatsapp' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo esc_html( $v1::get( 'cta_btn_text' ) ); ?>
						</a>
					</div>
					<?php $features = $v1::lines( 'cta_features', 2 ); ?>
					<?php if ( $features ) : ?>
						<ul class="tdt-cta__features">
							<?php foreach ( $features as $feature ) : ?>
								<li><?php echo $v1::icon( $feature[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $feature[1] ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php if ( $quote_on && $v1::wa( 'quote_wa_text' ) ) : ?>
	<script>
		( function () {
			var form = document.querySelector( '.tdt-quote__form' );
			if ( ! form ) {
				return;
			}
			form.addEventListener( 'submit', function ( e ) {
				e.preventDefault();
				var text = 'Hola, quiero una cotización:' +
					'\n• Producto: ' + form.producto.value +
					'\n• Cantidad: ' + form.cantidad.value +
					'\n• Departamento: ' + form.departamento.value +
					'\n• Ciudad: ' + form.ciudad.value;
				window.open( 'https://wa.me/' + form.dataset.whatsapp + '?text=' + encodeURIComponent( text ), '_blank', 'noopener' );
			} );
		} )();
	</script>
<?php endif; ?>
<?php
get_footer();
