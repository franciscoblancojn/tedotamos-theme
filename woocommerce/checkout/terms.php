<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>
	<div class="woocommerce-terms-and-conditions-wrapper">
		<?php
		/**
		 * Terms and conditions hook used to inject content.
		 *
		 * @since 3.4.0.
		 * @hooked wc_checkout_privacy_policy_text() Shows custom privacy policy text. Priority 20.
		 * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
		 */
		do_action( 'woocommerce_checkout_terms_and_conditions' );
		?>

		<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
			<?php
			// TeDotamos: en "Pagar el pedido" (order-pay, p. ej. el envío de contraentrega) los términos
			// ya se aceptaron al crear el pedido: la casilla sale marcada y bloqueada, y se envía igual
			// mediante el campo oculto "terms" (un checkbox disabled no se envía).
			$tdt_terms_locked = function_exists( 'is_checkout_pay_page' ) && is_checkout_pay_page();
			?>
			<p class="form-row validate-required">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( $tdt_terms_locked || apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> <?php disabled( $tdt_terms_locked, true ); ?> id="terms" />
                    <span class="woocommerce-terms-and-conditions-checkbox-text"><?php wc_terms_and_conditions_checkbox_text(); ?><span class="required">*</span></span>
                </label>
				<input type="hidden" name="terms-field" value="1" />
				<?php if ( $tdt_terms_locked ) : ?>
					<input type="hidden" name="terms" value="1" />
				<?php endif; ?>
			</p>
		<?php endif; ?>
	</div>
	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
