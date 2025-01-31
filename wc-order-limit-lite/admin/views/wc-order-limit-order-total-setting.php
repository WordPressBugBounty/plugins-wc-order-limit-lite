<?php
/**
 *
 * Order total limit page of plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin/views
 */

if ( ! isset( $wcol_settings['enable_cart_total_limit'] ) ) {
	$style = "style='pointer-events: none;opacity:0.5;'";
	echo '<span style="color:red"><strong>' . esc_html__( 'Note! ', 'order-limit-for-woocommerce' ) . '</strong>' . esc_html__( 'Order Total Limits are Disabled.', 'order-limit-for-woocommerce' ) . '</span>';
	?>
	<div class="wcol-help-tip" style="float:none; margin-right:0;">
		<span class="wcol-tip" > <?php esc_html_e( 'Order Total Limits are disabled, You can enable Order Total Limits in Advanced Tab.', 'order-limit-for-woocommerce' ); ?> </span>
	</div>
	<?php
} else {
	$style = '';
}
?>
<h2>
	<?php esc_html_e( 'Order total Limit', 'order-limit-for-woocommerce' ); ?>
	<a class="xs-pro-link xs-button-main" href="https://woocommerce.com/products/order-limit/" target="_blank">
		<input type="button" name="xs-button" id="xs-button" class="button" value="<?php esc_html_e( 'Pro Version', 'order-limit-for-woocommerce' ); ?>">
	</a>
</h2>
<input type="hidden" name="order_total_limit_settings" value='1'>
<table class="form-table" <?php echo esc_html( $style ); ?>>
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="cart_total_minimum_limit">
					<?php esc_html_e( 'Cart Total Minumum Limit:', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" tabindex="0" data-tip="<?php esc_html_e( 'Cart Total(amount or total Items in Cart) should be greater than or equal to this limit.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Cart Total(amount or total Items in Cart) should be greater than or equal to this limit.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>

			</th>
			<td>
				<input type="number" min="0" name="cart_total_minimum_limit" value="<?php echo isset( $wcol_settings['cart_total_minimum_limit'] ) ? esc_html( $wcol_settings['cart_total_minimum_limit'] ) : '0'; ?>"/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-cart-total-enable-single-cat-limit">
					<?php esc_html_e( 'Enable Cart Total Single Category Limit:', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" tabindex="0" data-tip="<?php esc_html_e( 'Check this box if you want to enable single category Limit for Cart Total.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Check this box if you want to enable single category Limit for Cart Total.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td>
				<input type="checkbox" class="wcol-cart-total-enable-single-cat-limit" name="cart_total_enable_single_cat_limit" <?php echo ( isset( $wcol_settings['cart_total_enable_single_cat_limit'] ) && 'on' === $wcol_settings['cart_total_enable_single_cat_limit'] ) ? 'checked' : ''; ?> />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-cart-total-enable-max-limit">
					<?php esc_html_e( 'Enable Cart Total Maximum Limit:', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" tabindex="0" data-tip="<?php esc_html_e( 'Check this box if you want to enable Maximum Limit for Cart Total.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Check this box if you want to enable Maximum Limit for Cart Total.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td>
				<input type="checkbox" class="enable-cart-total-max-rule-limit" name="cart_total_enable_maximum_limit" <?php echo ( isset( $wcol_settings['cart_total_enable_maximum_limit'] ) && 'on' === $wcol_settings['cart_total_enable_maximum_limit'] ) ? 'checked' : ''; ?>/>
			</td>
		</tr>
		<tr valign="top" class="<?php echo ( isset( $wcol_settings['cart_total_enable_maximum_limit'] ) && 'on' !== $wcol_settings['cart_total_enable_maximum_limit'] ) ? 'wcol-hidden' : ''; ?>">
			<th scope="row" class="titledesc">
				<label for="wcol-cart-total-max-limit">
					<?php esc_html_e( 'Cart Total Maximum Limit:', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" tabindex="0" data-tip="<?php esc_html_e( 'Cart Total(amount or total Items in Cart) should be less than or equal to this limit.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Cart Total(amount or total Items in Cart) should be less than or equal to this limit.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td>
				<input type="number" min="0" class="wcol-rule-max-limit" name="cart_total_maximum_limit" value="<?php echo isset( $wcol_settings['cart_total_maximum_limit'] ) ? esc_html( $wcol_settings['cart_total_maximum_limit'] ) : ''; ?>"/>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-cart-total-applied-on">
					<?php esc_html_e( 'Applied On', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" tabindex="0" data-tip="<?php esc_html_e( 'Select whether Min and Max limits for Cart Total will be applied on Cart total Amount or on total Items in Cart.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Select whether Min and Max limits for Cart Total will be applied on Cart total Amount or on total Items in Cart.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td>
				<select name="cart_total_applied_on">
					<option value="quantity" <?php echo ( isset( $wcol_settings['cart_total_applied_on'] ) && 'quantity' === $wcol_settings['cart_total_applied_on'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Total items in Cart', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="amount" <?php echo ( isset( $wcol_settings['cart_total_applied_on'] ) && 'amount' === $wcol_settings['cart_total_applied_on'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Cart Total', 'order-limit-for-woocommerce' ); ?>
					</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
