<?php
/**
 *
 * Shortcode page of plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin/views
 */

?>
<h2>
	<?php esc_html_e( 'Shortcode', 'order-limit-for-woocommerce' ); ?>
	<a class="xs-pro-link xs-button-main" href="https://woocommerce.com/products/order-limit/" target="_blank">
		<input type="button" name="xs-button" id="xs-button" class="button" value="<?php esc_html_e( 'Pro Version', 'order-limit-for-woocommerce' ); ?>">
	</a>
</h2>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row" class="titledesc" disabled>
				<?php esc_html_e( '[wc-order-limit rules="rule_ids"] (Pro)', 'order-limit-for-woocommerce' ); ?>
			</th>
			<td>
				<?php esc_html_e( '[wc-order-limit rules="rule_ids"] This is global shortcode when you insert in page it will show the limit of rule you have applied on product, categories and cutomers. This shortcode work basic of multiple rule. You can set text message Advance tab. Example of shortcode like [wc-order-limit rules="1,2,3,4"]', 'order-limit-for-woocommerce' ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" class="titledesc" disabled>
				<?php esc_html_e( '[wc-order-limit-remaining rule="rule_id"] (Pro)', 'order-limit-for-woocommerce' ); ?>
			</th>
			<td>
				<?php esc_html_e( '[wc-order-limit-remaining rule="rule_id"] This Shortcode show only remaining limit of products, categories and customer order.It work basic on single rule id. Example of shortcode like [wc-order-limit-remaining  rule="1"]', 'order-limit-for-woocommerce' ); ?>
			</td>
		</tr>
	</tbody>
</table>
