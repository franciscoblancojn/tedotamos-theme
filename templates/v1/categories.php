<?php
/**
 * TeDotamos: página de categorías de la plantilla V1.
 *
 * Se usa en las páginas con la plantilla "Categorías (TeDotamos V1)" (la crea el botón de
 * Apariencia → TeDotamos → Plantilla V1 → Configuración inicial). Los botones de la portada
 * que van "a la tienda" llevan aquí.
 *
 * Muestra todas las categorías principales con sus subcategorías; el contenido de la página
 * (si se escribe algo en el editor) aparece arriba.
 *
 * @package TeDotamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$v1         = 'TeDotamos_Template_V1';
$categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'parent'     => 0,
		'hide_empty' => true,
		'menu_order' => 'ASC',
		'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
	)
);

get_header();
?>
<main class="tdt-home tdt-categories-page">
	<section class="tdt-page-head">
		<div class="tdt-container">
			<h1><?php the_title(); ?></h1>
			<?php
			while ( have_posts() ) {
				the_post();
				if ( get_the_content() ) {
					echo '<div class="tdt-page-head__text">';
					the_content();
					echo '</div>';
				}
			}
			?>
			<div class="tdt-actions">
				<a class="tdt-btn tdt-btn--primary" href="<?php echo esc_url( $v1::shop_url() ); ?>"><?php echo $v1::icon( 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> Ver todos los productos</a>
				<?php if ( $v1::offers_term() ) : ?>
					<a class="tdt-btn tdt-btn--outline" href="<?php echo esc_url( $v1::offers_url() ); ?>"><?php echo $v1::icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> Ofertas</a>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="tdt-section">
		<div class="tdt-container">
			<?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
				<?php echo $v1::category_cards( $categories, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<p>Todavía no hay categorías con productos.</p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
