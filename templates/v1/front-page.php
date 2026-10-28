<?php
/**
 * TeDotamos: portada de la plantilla V1.
 *
 * Se usa en lugar de la portada de Elementor cuando la plantilla V1 está activa y
 * "Usar la portada V1" está marcado (TeDotamos_Template_V1::front_page_template()).
 * Contenido: Apariencia → TeDotamos → Plantilla V1.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$v1       = 'TeDotamos_Template_V1';
$shop_url = $v1::shop_url();
$wa_url   = $v1::whatsapp_url( 'Hola, quiero una cotización para mi empresa.' );
$quote_on = $v1::get( 'quote_enabled' );
$quote_to = $wa_url ? $wa_url : ( $quote_on ? '#tdt-quote' : $shop_url );

get_header();
?>
<main class="tdt-home">

	<?php if ( $v1::get( 'hero_enabled' ) ) : ?>
		<?php $hero_image = $v1::get( 'hero_image' ); ?>
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
							<a class="tdt-btn tdt-btn--primary" href="<?php echo esc_url( $v1::get( 'hero_btn1_url' ) ? $v1::get( 'hero_btn1_url' ) : $shop_url ); ?>">
								<?php echo $v1::icon( 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo esc_html( $v1::get( 'hero_btn1_text' ) ); ?>
							</a>
						<?php endif; ?>
						<?php if ( $v1::get( 'hero_btn2_text' ) ) : ?>
							<a class="tdt-btn tdt-btn--ghost" href="<?php echo esc_url( $v1::get( 'hero_btn2_url' ) ? $v1::get( 'hero_btn2_url' ) : $quote_to ); ?>">
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

	<?php $rows = $v1::lines( 'volume_rows', 4 ); ?>
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
									<tr><th>Cantidad total</th><th>Descuento</th><th>Modalidad</th><th>Pago</th></tr>
								</thead>
								<tbody>
									<?php foreach ( $rows as $row ) : ?>
										<tr>
											<td><?php echo esc_html( $row[0] ); ?></td>
											<td><strong><?php echo esc_html( $row[1] ); ?></strong></td>
											<td><?php echo esc_html( $row[2] ); ?></td>
											<td><?php echo esc_html( $row[3] ); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
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
					<a href="<?php echo esc_url( $shop_url ); ?>">Ver todas las categorías <?php echo $v1::icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</header>
				<ul class="tdt-cats">
					<?php foreach ( $categories as $cat ) : ?>
						<?php $thumb_id = get_term_meta( $cat->term_id, 'thumbnail_id', true ); ?>
						<li>
							<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
								<span class="tdt-cats__img">
									<?php
									echo $thumb_id
										? wp_get_attachment_image( $thumb_id, 'woocommerce_thumbnail', false, array( 'alt' => $cat->name ) )
										: wc_placeholder_img( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</span>
								<span class="tdt-cats__body">
									<strong><?php echo esc_html( $cat->name ); ?></strong>
									<?php if ( $cat->description ) : ?>
										<span><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $cat->description ), 10 ) ); ?></span>
									<?php endif; ?>
									<?php echo $v1::icon( 'arrow', 'tdt-icon tdt-cats__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php $products = $v1::get( 'products_enabled' ) ? $v1::products() : array(); ?>
	<?php if ( $products ) : ?>
		<section class="tdt-section">
			<div class="tdt-container">
				<header class="tdt-section__head">
					<h2><?php echo esc_html( $v1::get( 'products_title' ) ); ?></h2>
					<a href="<?php echo esc_url( $shop_url ); ?>">Ver todos los productos <?php echo $v1::icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</header>
				<ul class="tdt-products">
					<?php foreach ( $products as $product ) : ?>
						<li class="tdt-product">
							<?php if ( $product->is_on_sale() ) : ?>
								<span class="tdt-badge">Oferta</span>
							<?php endif; ?>
							<a class="tdt-product__img" href="<?php echo esc_url( $product->get_permalink() ); ?>">
								<?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<h3 class="tdt-product__title"><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
							<?php if ( $product->get_sku() ) : ?>
								<span class="tdt-product__sku">Ref. <?php echo esc_html( $product->get_sku() ); ?></span>
							<?php endif; ?>
							<span class="tdt-product__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
							<a class="tdt-btn tdt-btn--primary tdt-btn--block" href="<?php echo esc_url( $product->get_permalink() ); ?>">
								Ver producto <?php echo $v1::icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $quote_on ) : ?>
		<section class="tdt-section" id="tdt-quote">
			<div class="tdt-container">
				<div class="tdt-quote">
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
					</div>
					<?php if ( $wa_url ) : ?>
						<form class="tdt-quote__form" data-whatsapp="<?php echo esc_attr( $v1::get( 'quote_whatsapp' ) ); ?>">
							<p class="tdt-quote__form-title"><?php echo $v1::icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> Cuéntanos qué necesitas</p>
							<label>Producto o referencia<input type="text" name="producto" required></label>
							<label>Cantidad<input type="text" name="cantidad" inputmode="numeric" required></label>
							<label>Ciudad de entrega<input type="text" name="ciudad" required></label>
							<button type="submit" class="tdt-btn tdt-btn--dark tdt-btn--block">Enviar por WhatsApp</button>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php $brands = $v1::get( 'brands_enabled' ) ? $v1::brands() : array(); ?>
	<?php if ( $brands ) : ?>
		<section class="tdt-section">
			<div class="tdt-container">
				<header class="tdt-section__head">
					<h2><?php echo esc_html( $v1::get( 'brands_title' ) ); ?></h2>
				</header>
				<ul class="tdt-brands">
					<?php foreach ( $brands as $brand ) : ?>
						<?php $logo = get_term_meta( $brand->term_id, 'thumbnail_id', true ); ?>
						<li>
							<a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" title="<?php echo esc_attr( $brand->name ); ?>">
								<?php echo $logo ? wp_get_attachment_image( $logo, 'medium', false, array( 'alt' => $brand->name ) ) : '<span>' . esc_html( $brand->name ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $v1::get( 'cta_enabled' ) ) : ?>
		<section class="tdt-section">
			<div class="tdt-container">
				<div class="tdt-cta">
					<span class="tdt-cta__icon"><?php echo $v1::icon( 'quote' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<div class="tdt-cta__text">
						<h2><?php echo esc_html( $v1::get( 'cta_title' ) ); ?></h2>
						<p><?php echo esc_html( $v1::get( 'cta_text' ) ); ?></p>
						<a class="tdt-btn tdt-btn--primary" href="<?php echo esc_url( $v1::get( 'cta_btn_url' ) ? $v1::get( 'cta_btn_url' ) : $quote_to ); ?>">
							<?php echo esc_html( $v1::get( 'cta_btn_text' ) ); ?> <?php echo $v1::icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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

<?php if ( $wa_url ) : ?>
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
					'\n• Ciudad: ' + form.ciudad.value;
				window.open( 'https://wa.me/' + form.dataset.whatsapp + '?text=' + encodeURIComponent( text ), '_blank', 'noopener' );
			} );
		} )();
	</script>
<?php endif; ?>
<?php
get_footer();
