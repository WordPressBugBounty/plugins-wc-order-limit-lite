<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin/views
 */

$wcol_options  = get_option( 'wcol_options' );
$wcol_settings = $wcol_options['wcol_settings'];
$args          = array(
	'fields'      => 'ids',
	'post_type'   => 'wcol_rule',
	'status'      => 'publish',
	'numberposts' => -1,
);
$rule_ids      = get_posts( $args );
$match_rules   = array();
foreach ( $rule_ids as $rule_id ) {
	$rules = get_post_meta( $rule_id, 'wcol-post-rules', true );
	if ( isset( $rules['rule-type'] ) && ( 'products' === $rules['rule-type'] || 'categories' === $rules['rule-type'] ) ) {
		$match_rules[] = $rule_id;
	}
}
$rule_ids     = $match_rules;
$product_rule = '';
if ( ! isset( $wcol_settings['enable_product_limit'] ) || 'on' !== $wcol_settings['enable_product_limit'] ) {
	$product_rule = 'disabled';
}
$category_rule = '';
if ( ! isset( $wcol_settings['enable_category_limit'] ) || 'on' !== $wcol_settings['enable_category_limit'] ) {
	$category_rule = 'disabled';
}
$customer_rule = '';
if ( ! isset( $wcol_settings['enable_customer_limit'] ) || 'on' !== $wcol_settings['enable_customer_limit'] ) {
	$customer_rule = 'disabled';
}
$vendor_rule = '';
if ( ! isset( $wcol_settings['enable_vendor_limit'] ) || 'on' !== $wcol_settings['enable_vendor_limit'] ) {
	$vendor_rule = 'disabled';
}

$installed_payment_methods = WC()->payment_gateways()->payment_gateways();
?>
<p class="form-field">
	<label>
		<?php esc_html_e( 'Select Rule Type', 'order-limit-for-woocommerce' ); ?>
	</label>
	<?php wp_nonce_field( 'wcol_save_rules', '_wcol_save_rules_nonce', true ); ?>
	<select class="wcol-select-rule-type" name="wcol-rules[rule-type]" >
		<option value="none"><?php esc_html_e( 'Select rule type', 'order-limit-for-woocommerce' ); ?></option>
		<?php
		if ( is_plugin_active( 'wc-vendors/class-wc-vendors.php' ) ) {
			?>
				<option value="vendor"
				<?php
				echo ( isset( $rule['rule-type'] ) && 'vendor' === $rule['rule-type'] ) ? 'selected="selected"' : '';
				echo esc_html( $vendor_rule );
				?>
				><?php esc_html_e( 'Vendors Rules', 'order-limit-for-woocommerce' ); ?></option>
				<?php
		}
		?>
		<option value="products" <?php echo ( isset( $rule['rule-type'] ) && 'products' === $rule['rule-type'] ) ? 'selected="selected"' : ''; ?> <?php echo esc_html( $product_rule ); ?>><?php esc_html_e( 'Products Rules', 'order-limit-for-woocommerce' ); ?></option>
		<option value="categories"
		<?php
		echo ( isset( $rule['rule-type'] ) && 'categories' === $rule['rule-type'] ) ? 'selected="selected"' : '';
		echo esc_html( $category_rule );
		?>
		><?php esc_html_e( 'Categories Rules', 'order-limit-for-woocommerce' ); ?></option>
		<option value="customer" disabled 
		<?php
		echo ( isset( $rule['rule-type'] ) && 'customer' === $rule['rule-type'] ) ? 'selected="selected"' : '';
		echo esc_html( $customer_rule );
		?>
		><?php esc_html_e( 'Customers Rules (Pro)', 'order-limit-for-woocommerce' ); ?></option>
	</select>
	<span class="wcol-help-tip" style="float:none; margin-right:0;">
		<span class="wcol-tip" > <?php esc_html_e( 'You can Enable/Disable Rule Type from Advanced Settings', 'order-limit-for-woocommerce' ); ?> </span>
	</span>
	<a class="xs-pro-link xs-button-main" href="https://woocommerce.com/products/order-limit/" target="_blank">
		<input type="button" name="xs-button" id="xs-button" class="button" value="<?php esc_html_e( 'Pro Version', 'order-limit-for-woocommerce' ); ?>">
	</a>
</p>
<table class="wp-list-table widefat fixed striped wcol-rule-table">
	<tbody class="wcol-main-body">
		<tr class="wcol-product-type <?php echo ( isset( $rule['rule-type'] ) && 'products' !== $rule['rule-type'] ) ? 'wcol_hidden' : ''; ?>">
			<th class="manage-column">  <?php esc_html_e( 'Product Type', 'order-limit-for-woocommerce' ); ?> </th>
			<td>
				<select class="wcol-select-product-type" name="wcol-rules[product-type]">
					<option value="simple" <?php echo ( isset( $rule['product-type'] ) && 'simple' === $rule['product-type'] ) ? 'selected' : ''; ?>><?php esc_html_e( 'Simple', 'order-limit-for-woocommerce' ); ?></option>
					<option value="variable" <?php echo ( isset( $rule['product-type'] ) && 'variable' === $rule['product-type'] ) ? 'selected' : ''; ?> disabled ><?php esc_html_e( 'Variable (Pro)', 'order-limit-for-woocommerce' ); ?></option>
				</select>
			</td>
		</tr>
		<tr class="wcol-select-parent-rule <?php echo ( isset( $rule['rule-type'] ) && ( 'products' !== $rule['rule-type'] || 'categories' !== $rule['rule-type'] ) ) ? 'wcol_hidden' : ''; ?>">
			<th class="manage-column">  <?php esc_html_e( 'Select dependent parent rule (Pro)', 'order-limit-for-woocommerce' ); ?> </th>
			<td>
				<select class="wcol-select-parent-rule" name="wcol-rules[parent-rule]" disabled>
					<option value=""><?php esc_html_e( 'Select Parent rule', 'order-limit-for-woocommerce' ); ?></option>
					<?php foreach ( $rule_ids as $rule_id ) { ?>
						<option value="<?php echo esc_attr( $rule_id ); ?>" <?php echo ( isset( $rule['parent-rule'] ) && intval( $rule['parent-rule'] ) === $rule_id ) ? 'selected' : ''; ?>><?php echo esc_html( get_the_title( $rule_id ) ); ?></option>
					<?php } ?>

				</select>
			</td>
		</tr>
		<tr>
			<th class="manage-column column-primary wcol-object-type-th">
				<?php esc_html_e( 'Select items', 'order-limit-for-woocommerce' ); ?>
			</th>
			<td class="column-primary">
				<select class="wcol-select-items" name="wcol-rules[obj_ids][]" multiple="multiple" <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?> >
					<?php
					$select_cus_type = '';
					if ( isset( $rule['obj_ids'] ) && is_array( $rule['obj_ids'] ) && isset( $rule['rule-type'] ) ) {
						$select_cus_type = '';
						foreach ( $rule['obj_ids'] as $item_id ) {
							if ( isset( $item_id ) && ! empty( $item_id ) ) {
								if ( '-1' === $item_id ) {
									$rule_type = $rule['rule-type'];
									if ( 'delivery' === $rule['rule-type'] ) {
										$rule_type = 'porducts';
									}
									?>

										<option value="<?php echo esc_html( $item_id ); ?>" selected="selected">
										<?php echo esc_html( 'All ' . $rule_type ); ?>
										</option>
										<?php
								} else {
									if ( 'categories' === $rule['rule-type'] ) {
										$item = get_term_by( 'id', $item_id, 'product_cat' );
										?>
											<option value="<?php echo esc_html( $item_id ); ?>" selected="selected">
											<?php echo esc_html( $item->name ); ?>
											</option>
											<?php
									}
									if ( 'products' === $rule['rule-type'] || 'delivery' === $rule['rule-type'] ) {
										$item = wc_get_product( $item_id );
										?>
											<option value="<?php echo esc_html( $item_id ); ?>" selected="selected">
											<?php echo esc_html( $item->get_name() ); ?>
											</option>
											<?php

									}
									if ( 'vendor' === $rule['rule-type'] ) {
										$item      = null;
										$user_info = get_userdata( $item_id );
										echo '<option value="' . esc_attr( $item_id ) . '" selected="selected">' . esc_html( $user_info->display_name ) . '</option>';
									}
									if ( 'customer' === $rule['rule-type'] ) {
											$item            = null;
											$select_cus_type = $item_id;
											echo '<option value="' . esc_attr( $item_id ) . '" selected="selected">' . esc_html( ucfirst( str_replace( '-', ' ', $item_id ) ) ) . '</option>';

									}
								}
							}
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="no-cus-edit">
			<th class="manage-column">
				<?php esc_html_e( 'Minimum Limit', 'order-limit-for-woocommerce' ); ?>
			</th>
			<td data-colname="<?php esc_html_e( 'Minimum Limit', 'order-limit-for-woocommerce' ); ?>">
				<input type="number" min="0" name="wcol-rules[min-rule-limit]" value="<?php echo ( isset( $rule['min-rule-limit'] ) ) ? esc_attr( $rule['min-rule-limit'] ) : ''; ?>" class="wcol-rule-min-limit <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>"/>
			</td>
		</tr>
		<tr>
			<th  class="manage-column">
				<?php esc_html_e( 'Applied on', 'order-limit-for-woocommerce' ); ?>
				<div class="wcol-help-tip" style="float:none; margin-right:0;">
					<span class="wcol-tip" > <?php esc_html_e( "Select  whether Min and Max limits will be applied on Product(s)'s Amount in cart or on Quantity of Product(s)'s Items in Cart.", 'order-limit-for-woocommerce' ); ?> </span>
				</div>
			</th>
			<td data-colname="<?php esc_html_e( 'Applied On', 'order-limit-for-woocommerce' ); ?>">
				<select class="no-cus-edit wcol-select-applied-on <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[applied_on]" >
					<option value="amount" <?php echo ( isset( $rule['applied_on'] ) && 'amount' === $rule['applied_on'] ) ? 'selected="selected"' : ''; ?>>
						<?php esc_html_e( 'Amount', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="quantity" <?php echo ( isset( $rule['applied_on'] ) && 'quantity' === $rule['applied_on'] ) ? 'selected="selected"' : ''; ?>>
						<?php esc_html_e( 'Quantity', 'order-limit-for-woocommerce' ); ?>
					</option>
					<?php do_action( 'wcol_applied_on_options' ); ?>
				</select>
				<select class="cus-edit wcol-select-applied-on <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[cus_applied_on]" >
					<option value="amount" <?php echo ( isset( $rule['cus_applied_on'] ) && 'amount' === $rule['cus_applied_on'] ) ? 'selected="selected"' : ''; ?>>
						<?php esc_html_e( 'Total Amount', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="quantity" <?php echo ( isset( $rule['cus_applied_on'] ) && 'quantity' === $rule['cus_applied_on'] ) ? 'selected="selected"' : ''; ?>>
						<?php esc_html_e( 'No. of orders', 'order-limit-for-woocommerce' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr class="procat">
			<th  class="manage-column">
				<?php esc_html_e( 'Accumulatively', 'order-limit-for-woocommerce' ); ?>
				<div class="wcol-help-tip" style="float:none; margin-right:0;">
					<span class="wcol-tip" > <?php esc_html_e( 'Either limits will be applied accomulatively or individually on selected Product categories. i.e if you check this box then accomulative total amount or quantity for selected Products will be considered rather than individual Product.', 'order-limit-for-woocommerce' ); ?> </span>
				</div>
			</th>
			<td  data-colname="<?php esc_html_e( 'Accumulatively', 'order-limit-for-woocommerce' ); ?>">
				<input type="checkbox" name="wcol-rules[accomulative]" class="wcol-accomulative wcol-loop-checkbox <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" <?php echo ( isset( $rule['accomulative'] ) && ! empty( $rule['accomulative'] ) ) ? 'checked="checked"' : ''; ?> />
			</td>
		</tr>
		<tr>
			<th class="manage-column"><?php esc_html_e( 'More Options', 'order-limit-for-woocommerce' ); ?></th>
			<td class="wcol-more-options-td">
				<div class="wcol-more-options">
					<a class="wcol-show-more-options" href="#"><?php esc_html_e( 'More Options', 'order-limit-for-woocommerce' ); ?></a>
					<a class="wcol-hide-more-options wcol-hidden" href="#"><?php esc_html_e( 'Hide Options', 'order-limit-for-woocommerce' ); ?></a>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<div class="wcol-rule-options wcol-hidden">
	<div class="wcol-more-options-header">
		<h3><?php esc_html_e( 'More Options', 'order-limit-for-woocommerce' ); ?></h3>
	</div>
	<table class="wp-list-table widefat fixed striped wcol_post_type_rule">
		<tr>
			<th><?php esc_html_e( 'Disable', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol-rules[disable-limit]" value="<?php echo isset( $rule['disable-limit'] ) ? esc_html( $rule['disable-limit'] ) : ''; ?>"/>
				<input class="wcol-disable-rule-limit wcol-loop-checkbox" type="checkbox" <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'checked' : ''; ?>/>
			</td>
		</tr>
		<tr class="procat">
			<th>
				<?php esc_html_e( 'Check Previous Orders?', 'order-limit-for-woocommerce' ); ?>
				<div class="wcol-help-tip" style="float:none; margin-right:0;">
					<span class="wcol-tip" > <?php esc_html_e( 'Minimum Limits will be ignored if this option is enabled.', 'order-limit-for-woocommerce' ); ?> </span>
				</div>
			</th>
			<td>
				<select class="across-all-orders-limit <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[across-all-orders]" >
					<option value="1" <?php echo ( isset( $rule['across-all-orders'] ) && '1' === $rule['across-all-orders'] ) ? 'selected="selected"' : ''; ?>>
						<?php esc_html_e( 'None', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="2" <?php echo ( isset( $rule['across-all-orders'] ) && ( '2' === $rule['across-all-orders'] || 'on' === $rule['across-all-orders'] ) ) ? 'selected="selected"' : ''; ?> disabled>
						<?php esc_html_e( 'Previous Orders  of Current User (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="3" <?php echo ( isset( $rule['across-all-orders'] ) && '3' === $rule['across-all-orders'] ) ? 'selected="selected"' : ''; ?> disabled>
						<?php esc_html_e( 'Previous Orders  of All Users (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr class="no-cus-edit">
			<th><?php esc_html_e( 'Enable Maximum Limit', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input type="hidden" class="enable-max-rule-limit-hidden wcol-loop-checkbox-hidden" name="wcol-rules[enable-max-rule-limit]" value="<?php echo isset( $rule['enable-max-rule-limit'] ) ? esc_attr( $rule['enable-max-rule-limit'] ) : ''; ?>" />
				<input class="enable-max-rule-limit wcol-loop-checkbox
				<?php echo ( ( isset( $rule['across-all-orders'] ) && '1' !== $rule['across-all-orders'] ) || ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ) ? 'wcol-disabled' : ''; ?>" type="checkbox" <?php echo ( isset( $rule['enable-max-rule-limit'] ) && 'on' === $rule['enable-max-rule-limit'] ) ? 'checked' : ''; ?>/>
			</td>
		</tr>
		<tr class="no-cus-edit <?php echo ( isset( $rule['enable-max-rule-limit'] ) && 'on' !== $rule['enable-max-rule-limit'] ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Maximum Limit', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input type="number" min="0" class="wcol-rule-max-limit <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[max-rule-limit]" value="<?php echo isset( $rule['max-rule-limit'] ) ? esc_attr( $rule['max-rule-limit'] ) : ''; ?>"/>
			</td>
		</tr>
		<tr class="variation-count <?php echo ( isset( $rule['product-type'] ) && 'variable' !== $rule['product-type'] ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Variation Count (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select name="wcol-rules[variation-count]" disabled>
					<option value="combine" <?php echo ( isset( $rule['variation-count'] ) && 'combine' === $rule['variation-count'] ) ? 'selected' : ''; ?>><?php esc_html_e( 'Combine', 'order-limit-for-woocommerce' ); ?></option>
					<option value="individual" <?php echo ( isset( $rule['variation-count'] ) && 'individual' === $rule['variation-count'] ) ? 'selected' : ''; ?>><?php esc_html_e( 'Individual', 'order-limit-for-woocommerce' ); ?>
				</select>
			</td>
		</tr>

		<tr>
			<th><?php esc_html_e( 'Enable Time Span', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input type="hidden" class="enable-time-limit-hidden wcol-loop-checkbox-hidden" name="wcol-rules[enable-time-limit]" value="<?php echo isset( $rule['enable-time-limit'] ) ? esc_attr( $rule['enable-time-limit'] ) : ''; ?>" />
				<input class="enable-time-limit wcol-loop-checkbox <?php echo ( ( isset( $rule['across-all-orders'] ) && '1' !== $rule['across-all-orders'] ) || ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ) ? 'wcol-disabled' : ''; ?>" type="checkbox" <?php echo ( isset( $rule['enable-time-limit'] ) && 'on' === $rule['enable-time-limit'] ) ? 'checked' : ''; ?>/>
			</td>
		</tr>
		<tr class="<?php echo ( ! isset( $rule['enable-time-limit'] ) || ( isset( $rule['enable-time-limit'] ) && 'on' !== $rule['enable-time-limit'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Select Time Span', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-rule-time-span <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[rule-time-span]">
					<option value="daily" <?php echo ( isset( $rule['rule-time-span'] ) && 'daily' === $rule['rule-time-span'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Daily', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="weekly" <?php echo ( isset( $rule['rule-time-span'] ) && 'weekly' === $rule['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
						<?php esc_html_e( 'Weekly (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="monthly" <?php echo ( isset( $rule['rule-time-span'] ) && 'monthly' === $rule['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
						<?php esc_html_e( 'Monthly (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="yearly" <?php echo ( isset( $rule['rule-time-span'] ) && 'yearly' === $rule['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
						<?php esc_html_e( 'Yearly (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="days" <?php echo ( isset( $rule['rule-time-span'] ) && 'days' === $rule['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
						<?php esc_html_e( 'Custom Days (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="custom" <?php echo ( isset( $rule['rule-time-span'] ) && 'custom' === $rule['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
						<?php esc_html_e( 'Custom Date (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr class="<?php echo ( ! isset( $rule['enable-time-limit'] ) || ( isset( $rule['rule-time-span'] ) && 'yearly' !== $rule['rule-time-span'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Year Start Date (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input type="number" max="31" min="01" class="wcol-yearly-start-day <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[yearly-start-day]" disabled value="<?php echo isset( $rule['yearly-start-day'] ) ? esc_attr( $rule['yearly-start-day'] ) : ''; ?>"/>
				<select class="wcol-yearly-start-month <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[yearly-start-month]" disabled>
					<?php
					if ( is_array( $months_array ) ) {
						foreach ( $months_array as $key => $value ) {
							?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php echo( isset( $rule['yearly-start-month'] ) && $rule['yearly-start-month'] === $key ) ? 'selected' : ''; ?>>
								<?php echo esc_html( $value ); ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>

		<tr class="<?php echo ( ! isset( $rule['enable-time-limit'] ) || ( isset( $rule['rule-time-span'] ) && 'weekly' !== $rule['rule-time-span'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Week Start Day (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-weekly-start-day <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>"  disabled name="wcol-rules[weekly-start-day]">
					<?php
					if ( is_array( $weeks_array ) ) {
						foreach ( $weeks_array as $key => $value ) {
							?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php echo ( isset( $rule['weekly-start-day'] ) && $rule['weekly-start-day'] === $key ) ? 'selected' : ''; ?>>
								<?php echo esc_html( $value ); ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="<?php echo ( ! isset( $rule['enable-time-limit'] ) || ( isset( $rule['rule-time-span'] ) && 'monthly' !== $rule['rule-time-span'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Month Start Date (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td><input class="wcol-monthly-start-date <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" type="number" disabled min="01" max="31" name="wcol-rules[monthly-start-date]" value="<?php echo isset( $rule['monthly-start-date'] ) ? esc_attr( $rule['monthly-start-date'] ) : ''; ?>"/></td>
		</tr>
		<tr class="<?php echo ( ( ! isset( $rule['enable-time-limit'] ) || 'on' !== $rule['enable-time-limit'] ) || ( isset( $rule['rule-time-span'] ) && 'days' !== $rule['rule-time-span'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Number of days (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td><input type="number" min='0' class="wcol-rule-days  <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" disabled name="wcol-rules[days]" value="<?php echo isset( $rule['days'] ) ? esc_attr( $rule['days'] ) : ''; ?>"/></td>
		</tr>
		<tr class="<?php echo ( ( ! isset( $rule['enable-time-limit'] ) || 'on' !== $rule['enable-time-limit'] ) || ( isset( $rule['rule-time-span'] ) && 'custom' !== $rule['rule-time-span'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Start Date (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td><input class="wcol-rule-start-time <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" disabled name="wcol-rules[rule-start-time]" value="<?php echo isset( $rule['rule-start-time'] ) ? esc_attr( $rule['rule-start-time'] ) : ''; ?>"/></td>
		</tr>

		<tr class="<?php echo ( ( ! isset( $rule['enable-time-limit'] ) || 'on' !== $rule['enable-time-limit'] ) || ( isset( $rule['rule-time-span'] ) && 'custom' !== $rule['rule-time-span'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'End Date (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td><input class="wcol-rule-end-time <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" disabled name="wcol-rules[rule-end-time]" value="<?php echo isset( $rule['rule-end-time'] ) ? esc_attr( $rule['rule-end-time'] ) : ''; ?>"/></td>
		</tr>
		<tr class="procat">
			<th><?php esc_html_e( 'Enable for Users', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol-rules[enable-for-users]" value="<?php echo ( isset( $rule['enable-for-users'] ) && 'on' === $rule['enable-for-users'] ) ? esc_attr( $rule['enable-for-users'] ) : ''; ?>"/>
				<input class="enable-users-limit wcol-loop-checkbox <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" type="checkbox"<?php echo ( isset( $rule['enable-for-users'] ) && 'on' === $rule['enable-for-users'] ) ? 'checked' : ''; ?>/>
			</td>
		</tr>

		<tr class="procat <?php echo ( ! isset( $rule['enable-for-users'] ) || 'on' !== $rule['enable-for-users'] ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'User Type ', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-rule-user-type <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>"  name="wcol-rules[user-type]">
					<option value="" <?php echo ( isset( $rule['user-type'] ) && 'guest-users' === $rule['user-type'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'None', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="guest-users" <?php echo ( isset( $rule['user-type'] ) && 'guest-users' === $rule['user-type'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Guest Users Only (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="specific-users" <?php echo ( isset( $rule['user-type'] ) && 'specific-users' === $rule['user-type'] ) ? 'selected' : ''; ?> disabled>
						<?php esc_html_e( 'Specific Users Only (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="specific-roles" <?php echo ( isset( $rule['user-type'] ) && 'specific-roles' === $rule['user-type'] ) ? 'selected' : ''; ?> disabled>
						<?php esc_html_e( 'Specific Roles Only (Pro)', 'order-limit-for-woocommerce' ); ?>
					</option>
					<?php if ( is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) { ?>
						<option value="membership" <?php echo ( isset( $rule['user-type'] ) && 'membership' === $rule['user-type'] ) ? 'selected' : ''; ?> disabled>
							<?php esc_html_e( 'Membership Users (Pro)', 'order-limit-for-woocommerce' ); ?>
						</option>
					<?php } ?>
					<option value="all-users" <?php echo ( isset( $rule['user-type'] ) && 'all-users' === $rule['user-type'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'All Users', 'order-limit-for-woocommerce' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr class="procat <?php echo ( ( ! isset( $rule['enable-for-users'] ) || 'on' !== $rule['enable-for-users'] ) || ( isset( $rule['user-type'] ) && 'guest-users' !== $rule['user-type'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Guest Users (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-select-guest-users wcol-rule-guest-users <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[rule-guest-users][]" multiple="multiple"  disabled>
					<option value="ip" <?php echo ( isset( $rule['rule-guest-users'] ) && in_array( 'ip', $rule['rule-guest-users'], true ) ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'IP Address', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="cookies" <?php echo ( isset( $rule['rule-guest-users'] ) && in_array( 'cookies', $rule['rule-guest-users'], true ) ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Cookies', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="email" <?php echo ( isset( $rule['rule-guest-users'] ) && in_array( 'email', $rule['rule-guest-users'], true ) ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Email', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="phone" <?php echo ( isset( $rule['rule-guest-users'] ) && in_array( 'phone', $rule['rule-guest-users'], true ) ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Phone number', 'order-limit-for-woocommerce' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr class="procat <?php echo ( ( ! isset( $rule['enable-for-users'] ) || 'on' !== $rule['enable-for-users'] ) || ( isset( $rule['user-type'] ) && 'specific-users' !== $rule['user-type'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Users (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-select-users wcol-rule-users <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[rule-users][]" multiple="multiple" disabled>
					<?php
					if ( isset( $rule['rule-users'] ) && is_array( $rule['rule-users'] ) ) {
						foreach ( $rule['rule-users'] as $user_id ) {
								$user_info = get_userdata( $user_id );
								echo '<option value="' . esc_attr( $user_id ) . '" selected="selected">' . esc_html( $user_info->user_login ) . '</option>';

						}
					}
					?>
				</select>
			</td>
		</tr>

		<tr class="procat <?php echo ( ( ! isset( $rule['enable-for-users'] ) || 'on' !== $rule['enable-for-users'] ) || ( isset( $rule['user-type'] ) && 'specific-roles' !== $rule['user-type'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Roles (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-select-roles wcol-rule-roles <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[rule-roles][]" multiple="multiple"  disabled>
					<?php
					if ( isset( $rule['rule-roles'] ) && is_array( $rule['rule-roles'] ) ) {
						foreach ( $rule['rule-roles'] as $user_role ) {
							echo '<option value="' . esc_attr( $user_role ) . '" selected="selected">' . esc_html( ucfirst( $user_role ) ) . '</option>';
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="procat <?php echo ( ( ! isset( $rule['enable-for-users'] ) || 'on' !== $rule['enable-for-users'] ) || ( isset( $rule['user-type'] ) && 'membership' !== $rule['user-type'] ) ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Memberships (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-select-memberships wcol-rule-memberships <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[rule-memberships][]" multiple="multiple" disabled>
					<?php
					if ( isset( $rule['rule-memberships'] ) && is_array( $rule['rule-memberships'] ) ) {
						foreach ( $rule['rule-memberships'] as $membership ) {
							echo '<option value="' . esc_attr( $membership ) . '" selected="selected">' . esc_html( get_the_title( $membership ) ) . '</option>';

						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Enable for Payment Method', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol-rules[enable-for-payment]" value="<?php echo ( isset( $rule['enable-for-payment'] ) && 'on' === $rule['enable-for-payment'] ) ? esc_attr( $rule['enable-for-payment'] ) : ''; ?> "/>
				<input class="enable-payment-limit wcol-loop-checkbox  <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" type="checkbox" <?php echo ( isset( $rule['enable-for-payment'] ) && 'on' === $rule['enable-for-payment'] ) ? 'checked' : ''; ?>/>
			</td>
		</tr>
		<tr class="wcol-payment-method <?php echo ( ! isset( $rule['enable-for-payment'] ) || 'on' !== $rule['enable-for-payment'] ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Payment Method (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-rule-payment-type <?php echo ( isset( $rule['disable-limit'] ) && 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[payment-method][]" multiple="multiple" disabled>
					<?php
					if ( is_array( $installed_payment_methods ) && ! empty( $installed_payment_methods ) ) {
						foreach ( $installed_payment_methods as $key => $method ) {
							$selected = '';
							if ( isset( $rule['payment-method'] ) && in_array( $key, $rule['payment-method'], true ) ) {
								$selected = 'selected="selected"';
							}
							echo "<option value='" . esc_attr( $key ) . "' " . esc_attr( $selected ) . '>' . esc_html( $method->title ) . '</option>';
						}
					}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>
