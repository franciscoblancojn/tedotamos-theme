<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

$next_text = sprintf( '%s <i class="icon-chevron-right"></i>', esc_html__( 'Next Page', 'martfury' ) );
$prev_text = sprintf( '<i class="icon-chevron-left"></i> %s', esc_html__( 'Previous Page', 'martfury' ) );

$type = 'list';
if ( $total <= 1 ) {
	return;
}

// TeDotamos: en scroll infinito solo se imprime el enlace a la página siguiente (Martfury
// imprimía también los números). El JS de Martfury lo pulsa al verlo en pantalla, añade los
// productos y reemplaza este <nav> por el de la página cargada; por eso en la última página se
// imprime vacío (si no, el enlace anterior quedaría y volvería a cargar la última página).
// Sin JS, o para los buscadores, es un enlace normal a la página siguiente.
if ( martfury_get_option( 'catalog_nav_type' ) == 'infinite' ) {
	printf(
		'<nav class="woocommerce-pagination tdt-infinite" aria-label="%s">%s</nav>',
		esc_attr__( 'Product Pagination', 'woocommerce' ),
		$current < $total ? sprintf(
			'<a class="next page-numbers" href="%s"><span id="martfury-products-loading" class="tdt-infinite__loading">%s</span></a>',
			esc_url( str_replace( '%#%', $current + 1, $base ) ),
			esc_html__( 'Loading more products', 'martfury' )
		) : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
	return;
}
?>
<nav class="woocommerce-pagination" aria-label="<?php esc_attr_e( 'Product Pagination', 'woocommerce' ); ?>">
	<?php
	echo paginate_links( apply_filters( 'woocommerce_pagination_args', array( // WPCS: XSS ok.
		'base'      => $base,
		'format'    => $format,
		'add_args'  => false,
		'current'   => max( 1, $current ),
		'total'     => $total,
		'prev_text' => $prev_text,
		'next_text' => $next_text,
		'type'      => $type,
		'end_size'  => 3,
		'mid_size'  => 3,
	) ) );
	?>
</nav>
