<?php
/**
 *
 * Setting page of plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin/views
 */

?>
<h2>
	<?php esc_html_e( 'General Settings', 'order-limit-for-woocommerce' ); ?>
	<a class="xs-pro-link xs-button-main" href="https://woocommerce.com/products/order-limit/" target="_blank">
		<input type="button" name="xs-button" id="xs-button" class="button" value="<?php esc_html_e( 'Pro Version', 'order-limit-for-woocommerce' ); ?>">
	</a>
</h2>
<h2>
	<?php esc_html_e( 'Store Limit Options:', 'order-limit-for-woocommerce' ); ?>
</h2>
<input type="hidden" name="wcol_general_settings" value='1'>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="">
					<?php esc_html_e( 'Store Limits(Pro)', 'order-limit-for-woocommerce' ); ?>:
						<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Check this box to enable store based limits.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Check this box to enable store based limits.', 'order-limit-for-woocommerce' ); ?> "></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-checkbox" >
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Store Limits', 'order-limit-for-woocommerce' ); ?></span></legend>
					<label for="wcol-enable-product-limit">
						<input type="checkbox" disabled name="enable_store_limit"
						<?php
						if ( isset( $wcol_settings['enable_store_limit'] ) && 'on' === $wcol_settings['enable_store_limit'] ) {
							echo 'checked';}
						?>
						> <?php esc_html_e( 'Enable Store Limits', 'order-limit-for-woocommerce' ); ?>
					</label>
				</fieldset>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-store-limit-message">
					<?php esc_html_e( 'Message for Store limit(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the store limit.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="store_limit_message" disabled id="wcol-store-limit-message" rows="3"><?php echo isset( $wcol_settings['store_limit_message'] ) ? esc_html( $wcol_settings['store_limit_message'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {store-name} for Store Name', 'order-limit-for-woocommerce' ); ?>
					</span>
				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-store-limit-message-order-amount">
					<?php esc_html_e( 'Message for Store Total Order or Amount(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart or checkout page if customers maximum limit to order is reached and limit is being applied on total amount of orders in selected timespan.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="store_message_total_amount" disabled id="wcol-store-limit-message-order-amount" rows="3"><?php echo isset( $wcol_settings['store_message_total_amount'] ) ? esc_html( $wcol_settings['store_message_total_amount'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {store-name} for Store,{max-order-limit} is for Maximum orders limit,{max-amount-limit} is for Maximum amount limit,{time-span} for i.e Daily, Weekly,  Or Monthly {limit-reset-day} for date on which limit will be rest.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
	</body>
</table>
<h2><?php esc_html_e( 'Product Limit Options:', 'order-limit-for-woocommerce' ); ?></h2>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="">
					<?php esc_html_e( 'Products Limits', 'order-limit-for-woocommerce' ); ?>:
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Check this box to enable products based limits.', 'order-limit-for-woocommerce' ); ?>">  </span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-checkbox" >
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Products Limits', 'order-limit-for-woocommerce' ); ?></span></legend>
					<label for="wcol-enable-product-limit">
						<input type="checkbox" name="enable_product_limit"
						<?php
						if ( isset( $wcol_settings['enable_product_limit'] ) && 'on' === $wcol_settings['enable_product_limit'] ) {
							echo 'checked';}
						?>
						> <?php esc_html_e( 'Enable Products Limits', 'order-limit-for-woocommerce' ); ?>
					</label>
				</fieldset>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-product-limit-message">
					<?php esc_html_e( 'Message for Product limit', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for products.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="product_limit_message" id="wcol-product-limit-message" rows="3"><?php echo esc_html( $wcol_settings['product_limit_message'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {product-name} for Product Name, {min-limit} for Minimum Limit, {max-limit} for Maximum Limit {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-product-limit-message">
					<?php esc_html_e( 'Message for Parent Product limit(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for products.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="parent_product_limit_message" disabled id="wcol-product-limit-message" rows="3"><?php echo isset( $wcol_settings['parent_product_limit_message'] ) ? esc_html( $wcol_settings['parent_product_limit_message'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {product-name} for Product Name, {min-limit} for Minimum Limit, {max-limit} for Maximum Limit {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount,{parent-product-names} for parent rule Products.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-product-limit-message">
					<?php esc_html_e( 'Shortcode message for Product limit(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on where shortcode will be place.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="shortcode_product_limit_message" disabled id="wcol-product-limit-message" rows="3"><?php echo isset( $wcol_settings['shortcode_product_limit_message'] ) ? esc_html( $wcol_settings['shortcode_product_limit_message'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {product-name} for Product Name, {min-limit} for Minimum Limit, {max-limit} for Maximum Limit {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>
				</div>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-product-limit-message-across-all-orders">
					<?php esc_html_e( 'Message for Product limit For Previous Orders Of Current User Rules(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for products on Previous Orders Of Current User.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="product_limit_message_across_all_orders" disabled id="wcol-product-limit-message-across-all-orders" rows="3"><?php echo esc_html( $wcol_settings['product_limit_message_across_all_orders'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {product-name} for Product Name, {max-limit} for Maximum Limit, {applied-on} for quantity/amount, {time-span} for rule time span, {limit-reset-day} for rule rest date, {endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-product-limit-message">
					<?php esc_html_e( 'Shortcode message for Product limit For Previous Orders(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on where shortcode will be place.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="shortcode_product_limit_message_orders" disabled id="wcol-product-limit-message" rows="3"><?php echo isset( $wcol_settings['shortcode_product_limit_message_orders'] ) ? esc_html( $wcol_settings['shortcode_product_limit_message_orders'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {product-name} for Product Name, {min-limit} for Minimum Limit, {max-limit} for Maximum Limit {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>
				</div>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-product-limit-message-across-all-user-orders">
					<?php esc_html_e( 'Message for Product limit For Previous Orders Of All Users Rules(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for products on Previous Orders Of All Users Rules.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="product_limit_message_across_all_users_orders" disabled id="wcol-product-limit-message-across-all-users-orders" rows="3"><?php echo isset( $wcol_settings['product_limit_message_across_all_users_orders'] ) ? esc_html( $wcol_settings['product_limit_message_across_all_users_orders'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {product-name} for Product Name, {max-limit} for Maximum Limit, {applied-on} for quantity/amount, {time-span} for rule time span, {limit-reset-day} for rule rest date, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>
				</div>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-product-limit-accomulative">
					<?php esc_html_e( 'Message for Product limit For Accomulative Rules', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill rule for Acomulative Products.', 'order-limit-for-woocommerce' ); ?> "></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="product_limit_message_accomulative" id="wcol-product-limit-message-accomulative" rows="3"><?php echo esc_html( $wcol_settings['product_limit_message_accomulative'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {product-names} for Product Names seperated by comma, {max-limit} for Maximum Limit {min-limit} for Minimum Limit, {applied-on} for quantity/amount,  , {time-span} for rule time span, {limit-reset-day} for rule rest date, {endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
	</body>
</table>
<h2><?php esc_html_e( 'Category Limit Options:', 'order-limit-for-woocommerce' ); ?></h2>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-enable-category-limit">
					<?php esc_html_e( 'Category Limits', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Check this box to enable product categories based limits.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-checkbox" >
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Category Limits', 'order-limit-for-woocommerce' ); ?></span></legend>
					<label for="wcol-enable-category-limit">
						<input type="checkbox" name="enable_category_limit"
						<?php
						if ( isset( $wcol_settings['enable_category_limit'] ) && 'on' === $wcol_settings['enable_category_limit'] ) {
							echo 'checked';}
						?>
						> <?php esc_html_e( 'Enable Category Limits', 'order-limit-for-woocommerce' ); ?>
					</label>
				</fieldset>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-category-limit-message">
					<?php esc_html_e( 'Message for Category limit', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for product categories,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="category_limit_message" id="category_limit_message" rows="3"><?php echo esc_html( $wcol_settings['category_limit_message'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {category-name} for Category Name, {min-limit} for Minimum Limit, {max-limit} for Maximum Limit {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-category-limit-message">
					<?php esc_html_e( 'Message for Parent Category limit (Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for product categories,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="parent_category_limit_message" disabled id="category_limit_message" rows="3"><?php echo isset( $wcol_settings['parent_category_limit_message'] ) ? esc_html( $wcol_settings['parent_category_limit_message'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {category-name} for Category Name, {min-limit} for Minimum Limit, {max-limit} for Maximum Limit {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount,{parent-category-names} for parent rule category}', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-category-limit-message">
					<?php esc_html_e( 'Shortcode message for Category limit(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on page where you place on shortcode of order rule limit', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="shortcode_category_limit_message" disabled id="category_limit_message" rows="3"><?php echo isset( $wcol_settings['shortcode_category_limit_message'] ) ? esc_html( $wcol_settings['shortcode_category_limit_message'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {category-name} for Category Name, {min-limit} for Minimum Limit, {max-limit} for Maximum Limit {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-category-limit-message-across-all-orders">
					<?php esc_html_e( 'Message for Category limit for Previous Orders of Current User(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for product categories on Previous Orders of Current User,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="category_limit_message_across_all_orders" disabled id="wcol-category-limit-message-across-all-orders" rows="3"><?php echo esc_html( $wcol_settings['category_limit_message_across_all_orders'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {category-name} for Category Name, {max-limit} for Maximum Limit {applied-on} for quantity/amount, {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-product-limit-message">
					<?php esc_html_e( 'Shortcode message for Category limit For Previous Orders(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on where shortcode will be place.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="shortcode_category_limit_message_orders" disabled id="wcol-product-limit-message" rows="3"><?php echo isset( $wcol_settings['shortcode_category_limit_message_orders'] ) ? esc_html( $wcol_settings['shortcode_product_limit_message_orders'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {product-name} for Product Name, {min-limit} for Minimum Limit, {max-limit} for Maximum Limit {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-category-limit-message-across-all-users-orders">
					<?php esc_html_e( 'Message for Category limit for Previous Orders of All Users(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for product categories on Previous Orders of All Users.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="category_limit_message_across_all_users_orders" disabled id="wcol-category-limit-message-across-all-users-orders" rows="3"><?php echo isset( $wcol_settings['category_limit_message_across_all_users_orders'] ) ? esc_html( $wcol_settings['category_limit_message_across_all_users_orders'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {category-name} for Category Name, {max-limit} for Maximum Limit {applied-on} for quantity/amount, {time-span} for rule time span, {limit-reset-day} for rule rest date, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-category-limit-message-accomulative">
					<?php esc_html_e( 'Message for Category limit for Accomulative Rules', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill an accomulative rule for product categories,{endline} for line end, {remain-items} for remaining items.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="category_limit_message_accomulative" id="wcol-category-limit-message-accomulative" rows="3"><?php echo esc_html( $wcol_settings['category_limit_message_accomulative'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {category-names} for Category Names seperated by comma, {max-limit} for Maximum Limit, {min-limit} for Minimum Limit, {applied-on} for quantity/amount , {time-span} for rule time span, {limit-reset-day} for rule rest date,{endline} for line end, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
	</tbody>
</table>
<h2><?php esc_html_e( 'Customers Limit Options:', 'order-limit-for-woocommerce' ); ?></h2>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-enable-customer-limit">
					<?php esc_html_e( 'Customers limts (Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Check this box to enable limits on Customers.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-checkbox" >
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Customers Limits', 'order-limit-for-woocommerce' ); ?></span></legend>
					<label for="wcol-enable-customer-limit">
						<input type="checkbox" disabled name="enable_customer_limit"
						<?php
						if ( isset( $wcol_settings['enable_customer_limit'] ) && 'on' === $wcol_settings['enable_customer_limit'] ) {
							echo 'checked';}
						?>
						> <?php esc_html_e( 'Enable Customers Limits', 'order-limit-for-woocommerce' ); ?>
					</label>
				</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-monthly-limit-reset-date">
					<?php esc_html_e( 'Monthly Limit Reset Day(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'Day on which monthly order limits will be reset i,e 1st of everty month.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-checkbox" >
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Monthly Limit Reset Day', 'order-limit-for-woocommerce' ); ?></span></legend>
					<label for="wcol-monthly-limit-reset-date">
						<input type="number" min="1" max = "31" disabled name="monthly_limit_reset_date" value="<?php echo esc_html( $wcol_settings['monthly_limit_reset_date'] ); ?>" >
					</label>
				</fieldset>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-weekly-limit-reset-date">
					<?php esc_html_e( 'Weekly Limit Reset Day(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" class="<?php esc_html_e( 'Day on which weekly limits will be reset i.e Monday.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-checkbox" >
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Weekly Limit Reset Day', 'order-limit-for-woocommerce' ); ?></span></legend>
					<label for="wcol-weekly-limit-reset-date">
						<select name="weekly_limit_reset_date" disabled>
							<?php
							if ( is_array( $weeks_array ) ) {
								foreach ( $weeks_array as $key => $value ) {
									?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php echo ( $key === $wcol_settings['weekly_limit_reset_date'] ) ? 'selected' : ''; ?>>
										<?php echo esc_html( $value ); ?>
									</option>
									<?php
								}
							}
							?>
						</select>
					</label>
				</fieldset>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-customer-limit-message">
					<?php esc_html_e( 'Message for No. of orders (Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on checkout page if customers maximum limit to order is reached and limit is being applied on numbers of orders in selected timespan.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="customer_message" disabled id="wcol-customer-limit-message" rows="3"><?php echo esc_html( $wcol_settings['customer_message'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {rule-limit} for Limit, {time-span} for i.e Daily, Weekly,  Or Monthly {limit-reset-day} for date on which limit will be rest.{remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-customer-limit-message-order-amount">
					<?php esc_html_e( 'Message for Total Order Amount(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on checkout page if customers maximum limit to order is reached and limit is being applied on total amount of orders in selected timespan.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="customer_message_total_amount" disabled id="wcol-customer-limit-message-order-amount" rows="3"><?php echo esc_html( $wcol_settings['customer_message_total_amount'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {rule-limit} for Limit, {time-span} for i.e Daily, Weekly,  Or Monthly {limit-reset-day} for date on which limit will be rest, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-customer-limit-message">
					<?php esc_html_e( 'Shortcode message for No. of orders(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on checkout page if customers maximum limit to order is reached and limit is being applied on numbers of orders in selected timespan.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="shortcode_customer_message" disabled id="wcol-customer-limit-message" rows="3"><?php echo isset( $wcol_settings['shortcode_customer_message'] ) ? esc_html( $wcol_settings['shortcode_customer_message'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {rule-limit} for Limit, {time-span} for i.e Daily, Weekly,  Or Monthly {limit-reset-day} for date on which limit will be rest.{remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
		<tr valign="top" class="titledesc">
			<th scope="row">
				<label for="wcol-customer-limit-message-order-amount">
					<?php esc_html_e( 'Shortcode message for Total Order Amount(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on checkout page if customers maximum limit to order is reached and limit is being applied on total amount of orders in selected timespan.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="shortcode_customer_message_total_amount" disabled id="wcol-customer-limit-message-order-amount" rows="3"><?php echo isset( $wcol_settings['shortcode_customer_message_total_amount'] ) ? esc_html( $wcol_settings['shortcode_customer_message_total_amount'] ) : ''; ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {rule-limit} for Limit, {time-span} for i.e Daily, Weekly,  Or Monthly {limit-reset-day} for date on which limit will be rest, {remaining} for Remain quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>

				</div>
			</td>
		</tr>
	</body>
<table>
<h2><?php esc_html_e( 'Cart Total Limit Options:', 'order-limit-for-woocommerce' ); ?></h2>
<table class="form-table">
	<body>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-enable-cart-total-limit">
					<?php esc_html_e( 'Cart Total Limits', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" class="<?php esc_html_e( 'Check this box to enable limits on cart total.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-checkbox" >
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Cart Total Limits', 'order-limit-for-woocommerce' ); ?></span></legend>
					<label for="wcol-enable-cart-total-limit">
						<input type="checkbox" name="enable_cart_total_limit"
						<?php
						if ( isset( $wcol_settings['enable_cart_total_limit'] ) && 'on' === $wcol_settings['enable_cart_total_limit'] ) {
							echo 'checked';}
						?>
						> <?php esc_html_e( 'Enable Cart Total Limits', 'order-limit-for-woocommerce' ); ?>
					</label>
				</fieldset>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-cart-total-limit-message">
					<?php esc_html_e( 'Message for Cart Total', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for cart totals.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="cart_total_limit_message"  id="wcol-cart-total-limit-message" rows="3"><?php echo esc_html( $wcol_settings['cart_total_limit_message'] ); ?></textarea>
					<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Use {min-limit} for Minimum Limit, {max-limit} for Maximum Limit, {applied-on} for quantity/amount.', 'order-limit-for-woocommerce' ); ?>
					</span>
				</div>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-cart-total-limit-message">
					<?php esc_html_e( 'Message for Cart Total single Category', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This message will be shown on cart page if customer do not fulfill the order limit that you specified for single category in cart', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="wcol-forminp wcol-forminp-textarea">
				<div>
					<textarea name="cart_single_cat_limit_message"  id="wcol-cart-total-limit-message" rows="3"><?php echo isset( $wcol_settings['cart_single_cat_limit_message'] ) ? esc_html( $wcol_settings['cart_single_cat_limit_message'] ) : ''; ?></textarea>
				</div>
			</td>
		</tr>
	</body>
</table>
<h2><?php esc_html_e( 'Other Options:', 'order-limit-for-woocommerce' ); ?></h2>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-enable-checkout-button">
					<?php esc_html_e( 'Hide Checkout Button', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'If you check this checkbox then Checkout Button will be hidden on cart page if customer do not fulfill any of the limits that you specified.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td><input type="checkbox" name="enable_checkout_button"
			<?php
			if ( isset( $wcol_settings['enable_checkout_button'] ) && 'on' === $wcol_settings['enable_checkout_button'] ) {
				echo 'checked';}
			?>
			></td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-enable-checkout-redirect">
					<?php esc_html_e( 'Enable checkout page redirect', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'If you check this checkbox then Checkout page will be redirect on cart page if customer fulfill any of the limits that you specified.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td>
				<input type="checkbox" name="enable_checkout_redirect"
				<?php
				if ( isset( $wcol_settings['enable_checkout_redirect'] ) && 'on' === $wcol_settings['enable_checkout_redirect'] ) {
					echo 'checked';}
				?>
				>
				<br>
				<span style="display: inline-block; padding: 0 50px 0 5px; font-style: italic; font-size: 11px; ">
						<?php esc_html_e( 'Kindly disable if you use any plugin that direct goto checkout or bypass cart page. Because show error too many redirect.', 'order-limit-for-woocommerce' ); ?>
				</span>

			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-reset-time">
					<?php esc_html_e( 'Limit Reset Time', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" data-tip="<?php esc_html_e( 'This is reset Daily time of limit', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td>
				<div>
					<select name="reset_time" id ="wcol-customer-reset-time" >
						<option value="1" <?php echo ( isset( $wcol_settings['reset_time'] ) && '1' === $wcol_settings['reset_time'] ) ? 'selected' : ''; ?>><?php esc_html_e( 'Midnight (12:00 AM)', 'order-limit-for-woocommerce' ); ?></option>
						<option value="2" <?php echo ( isset( $wcol_settings['reset_time'] ) && '2' === $wcol_settings['reset_time'] ) ? 'selected' : ''; ?>><?php esc_html_e( 'Purchase time', 'order-limit-for-woocommerce' ); ?></option>
					</select>
				</div>
			</td>
		</tr>
	</tbody>
</table>
