<?php
/**
 * Product rule tab
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://xfiniftysoft.com
 * @since      1.0.0
 *
 * @package    wc_order_limit_lite
 * @subpackage wc_order_limit_lite/includes/admin/views/
 */

?>
<form action="" method="POST">
<input type="hidden" name='product_rules' value='1'>
<?php
	wp_nonce_field( 'wcol_save_rules', '_wcol_save_rules_nonce', true );
?>
<div class="wcol-rules-section wcol-products-section">
	<?php
	if ( 'on' !== $wcol_settings['enable_product_limit'] ) {
		$style = 'pointer-events: none;opacity:0.5;';
		echo '<span style="color:red"><strong>' . esc_html__( 'Note! ', 'wc-order-limit-lite' ) . '</strong>' . esc_html__( 'Product Limits are Disabled.', 'wc-order-limit-lite' ) . '</span>';
		?>
		<div class="wcol-help-tip" style="float:none; margin-right:0;">
			<span class="wcol-tip" > <?php esc_html_e( 'Product Limits are disabled, You can enable Product Limits in Advance Tab.', 'wc-order-limit-lite' ); ?> </span>
		</div>
		<?php
	} else {
		$style = '';
	}
	?>
	<table class="wp-list-table widefat fixed striped" style="<?php echo esc_attr( $style ); ?>">
		<thead>
			<tr>
				<th class="manage-column column-cb check-column wcol-select-all">
					<input type="checkbox"/>
				</th>
				<th class="manage-column column-primary wcol-object-type-th">
					<?php esc_html_e( 'Product', 'wc-order-limit-lite' ); ?>
				</th>
				<th style="" class="manage-column">	
					<?php esc_html_e( 'Minimum Limit', 'wc-order-limit-lite' ); ?>
				</th>
				<!--<th style="width:11%"><?php esc_html_e( 'Maximum Limit', 'wc-order-limit-lite' ); ?></th>-->
				<th style="" class="manage-column">
					<?php esc_html_e( 'Applied on', 'wc-order-limit-lite' ); ?>
					<div class="wcol-help-tip" style="float:none; margin-right:0;">
						<span class="wcol-tip" > <?php esc_html_e( "Select whether Min and Max limits will be applied on Product(s)'s Amount in cart or on Quantity of Product(s)'s Items in Cart.", 'wc-order-limit-lite' ); ?> </span>
					</div>
				</th>
				<th style="" class="manage-column">
					<?php esc_html_e( 'Accumulatively', 'wc-order-limit-lite' ); ?>
					<div class="wcol-help-tip" style="float:none; margin-right:0;">
						<span class="wcol-tip" > <?php esc_html_e( 'Either limits will be applied accomulatively or individually on selected Product categories. i.e if you check this box then accomulative total amount or quantity for selected Products will be considered rather than individual Product.', 'wc-order-limit-lite' ); ?> </span>
					</div>
				</th>
				<th class="manage-column"><?php esc_html_e( 'More Options', 'wc-order-limit-lite' ); ?></th>
			</tr>
		</thead>
		<tbody class="wcol-main-body">
			<?php
			$xs_i = 0;
			if ( is_array( $wcol_product_rules ) ) {
				foreach ( $wcol_product_rules as $rule ) {
					?>
				<tr data-id='<?php echo esc_attr( $rule['rule-id'] ); ?>'>
					<td class="check-column wcol-cb">
						<input type="hidden" name="wcol-rules[product-rules][rule-id][<?php echo esc_html( $xs_i ); ?>]" value="<?php echo esc_html( $rule['rule-id'] ); ?>"/>
						<input type="checkbox"/>
					</td>
					<td class="column-primary">
						<select class="wcol-select-products 
						<?php
						if ( 'on' === $rule['disable-limit'] ) {
							echo 'wcol-disabled';}
						?>
						" name="wcol-rules[product-rules][object-ids][<?php echo esc_html( $xs_i ); ?>][]" multiple="multiple">
						<?php
						if ( is_array( $rule['object_ids'] ) ) {
							foreach ( $rule['object_ids'] as $product_id ) {
								if ( isset( $product_id ) && ! empty( $product_id ) ) {
									if ( '-1' === $product_id ) {
										?>
											<option value="<?php echo esc_html( $product_id ); ?>" selected="selected"> 
											<?php esc_html_e( 'All Products', 'wc-order-limit-lite' ); ?>
											</option>
										<?php
									} else {
										$product = wc_get_product( $product_id );
										if ( null !== $product ) {
											?>
												<option value="<?php echo esc_html( $product_id ); ?>" selected="selected"> 
												<?php echo esc_html( get_the_title( $product_id ) ); ?>
												</option>
											<?php
										}
									}
								}
							}
						}
						?>
						</select>
						<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'wc-order-limit-lite' ); ?></span></button>
					</td>
					<td data-colname="<?php esc_attr_e( 'Minimum Limit', 'wc-order-limit-lite' ); ?>">
						<input type="number" min="0" class="wcol-rule-min-limit 
						<?php
						if ( 'on' === $rule['disable-limit'] ) {
							echo 'wcol-disabled';}
						?>
						" name="wcol-rules[product-rules][rule-limit][<?php echo esc_html( $xs_i ); ?>]" value="<?php echo esc_html( $rule['wcol_min_order_limit'] ); ?>" />
					</td>
					
					<td data-colname="<?php esc_attr_e( 'Applied On', 'wc-order-limit-lite' ); ?>">
						<?php
							$applied_on_options  = '';
							$applied_on_options .= '<option value="amount" ';
							$applied_on_options .= ( 'amount' === $rule['wcol_applied_on'] ) ? 'selected' : '';
							$applied_on_options .= '>' . esc_html__( 'Amount', 'wc-order-limit-lite' ) . '</option>';
							$applied_on_options .= '<option value="quantity" ';
							$applied_on_options .= ( 'quantity' === $rule['wcol_applied_on'] ) ? 'selected' : '';
							$applied_on_options .= ' >' . esc_html__( 'Quantity', 'wc-order-limit-lite' ) . '</option>';
							$applied_on_options  = apply_filters( 'wcol_applied_on_options', $applied_on_options, $rule );
						?>
						<select class="wcol-select-applied-on 
						<?php
						if ( 'on' === $rule['disable-limit'] ) {
							echo 'wcol-disabled';}
						?>
						" name="wcol-rules[product-rules][applied-on][<?php echo esc_html( $xs_i ); ?>]" >
							<?php
							//phpcs:ignore
							echo $applied_on_options; 
							?>
						</select>
					</td>
					<td data-colname="<?php esc_attr_e( 'Accumulatively', 'wc-order-limit-lite' ); ?>">
						<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol-rules[product-rules][accomulative][<?php echo esc_html( $xs_i ); ?>]" value="<?php echo esc_html( $rule['accomulative'] ); ?>"/>
						<input type="checkbox" class="wcol-accomulative wcol-loop-checkbox <?php echo ( 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>"  <?php echo ( 'on' === $rule['accomulative'] ) ? 'checked' : ''; ?> />
					</td>
					<td class="wcol-more-options-td">
						<div class="wcol-more-options">
							<a class="wcol-show-more-options" href="#">
							<?php
							if ( 'on' === $rule['disable-limit'] ) {
								esc_html_e( 'Enable this Limit', 'wc-order-limit-lite' );
							} else {
								esc_html_e( 'More Options', 'wc-order-limit-lite' );}
							?>
							</a>
							<a class="wcol-hide-more-options wcol-hidden" href="#"><?php esc_html_e( 'Hide Options', 'wc-order-limit-lite' ); ?></a>
							<div class="wcol-options-open wcol-hidden"></div>
							
							<div class=" wcol-rule-options wcol-hidden">
								<div class="wcol-more-options-header">
									<h3><?php esc_html_e( 'More Options', 'wc-order-limit-lite' ); ?></h3>
								</div>
								<table class="">
									<tr>
										<th><?php esc_html_e( 'Disable', 'wc-order-limit-lite' ); ?>:</th>
										<td>
											<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol-rules[product-rules][disable-limit][<?php echo esc_html( $xs_i ); ?>]" value="<?php echo esc_html( $rule['disable-limit'] ); ?>"/>
											<input class="wcol-disable-rule-limit wcol-loop-checkbox" type="checkbox" 
											<?php
											if ( 'on' === $rule['disable-limit'] ) {
												echo 'checked';}
											?>
											/>
										</td>
									</tr>
									<tr>
										<th><?php esc_html_e( 'Enable Maximum Limit', 'wc-order-limit-lite' ); ?>:</th>
										<td>
											<input type="hidden" class="enable-max-rule-limit-hidden wcol-loop-checkbox-hidden" name="wcol-rules[product-rules][enable-max-rule-limit][<?php echo esc_html( $xs_i ); ?>]" value="<?php echo esc_html( $rule['enable-max-rule-limit'] ); ?>" />
											<input class="enable-max-rule-limit wcol-loop-checkbox  <?php echo ( 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" type="checkbox" <?php echo ( 'on' === $rule['enable-max-rule-limit'] ) ? 'checked' : ''; ?> />
										</td>
									</tr>
									<tr class=" <?php echo ( 'on' !== $rule['enable-max-rule-limit'] ) ? 'wcol-hidden' : ''; ?>">
										<th><?php esc_html_e( 'Maximum Limit', 'wc-order-limit-lite' ); ?>:</th>
										<td><input type="number" min="0" class="wcol-rule-max-limit <?php ( 'on' === $rule['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[product-rules][max-rule-limit][<?php echo esc_html( $xs_i ); ?>]" value="<?php echo esc_html( $rule['wcol_max_order_limit'] ); ?>"/></td>
									</tr>
								</table>
							</div>
						</div>
					</td>
				</tr>
					<?php
					++$xs_i;
				}
			}
			?>
			<input type="hidden" class="xswcol-pid" value="<?php echo esc_html( $xs_i ); ?>" />
		</tbody>
		
	</table>
	<div class="wcol-actions-btn">
		<input type="submit" class="button button-primary button-large xs-wcol" value="<?php esc_html_e( 'Save', 'wc-order-limit-lite' ); ?>"/>
		<input type="button" class="button button-large wcol-delete-selected" value="<?php esc_html_e( 'Delete Selected', 'wc-order-limit-lite' ); ?>"/>
		<?php wp_nonce_field( 'wcol_new_rule', '_wcol_new_rule_nonce', true ); ?>
		<input type="button" id="wcol-add-product-rule" class="button button-primary button-large" value="<?php esc_html_e( 'Add New Rule', 'wc-order-limit-lite' ); ?>"/>
		<span class="spinner wcol_spinner"></span>
	</div>
</div>
</form>