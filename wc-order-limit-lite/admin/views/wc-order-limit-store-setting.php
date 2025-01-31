<?php
/**
 *
 * Store limit page of plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin/views
 */

if ( ! isset( $wcol_settings['enable_store_limit'] ) || 'on' !== $wcol_settings['enable_store_limit'] ) {
	$style = "style='pointer-events: none;opacity:0.5;'";
	echo '<span style="color:red"><strong>' . esc_html__( 'Note! ', 'order-limit-for-woocommerce' ) . '</strong>' . esc_html__( 'Store Limits are Disabled.', 'order-limit-for-woocommerce' ) . '</span>';
	?>
	<span class="woocommerce-help-tip" tabindex="0" data-tip="<?php esc_html_e( 'Store Limits are disabled, You can enable Store Limits in Advanced Tab.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Store Limits are disabled, You can enable Store Limits in Advanced Tab.', 'order-limit-for-woocommerce' ); ?>"></span>
	<?php
} else {
	$style = '';
}
?>
<h2>
	<?php esc_html_e( 'Store Limit', 'order-limit-for-woocommerce' ); ?>
	<a class="xs-pro-link xs-button-main" href="https://woocommerce.com/products/order-limit/" target="_blank">
		<input type="button" name="xs-button" id="xs-button" class="button" value="<?php esc_html_e( 'Pro Version', 'order-limit-for-woocommerce' ); ?>">
	</a>
</h2>
<input type="hidden" name="store_limit_settings" value="1">
<table class="form-table" <?php echo esc_html( $style ); ?> >
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-store-limit">
					<?php esc_html_e( 'Enable for Store Closed:(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" aria-label="<?php esc_html_e( 'If Checked this checkbox Store show are closed.', 'order-limit-for-woocommerce' ); ?>"  data-tip="<?php esc_html_e( 'If Checked this checkbox Store show are closed.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td class="forminp forminp-checkbox ">
				<input  disabled type="checkbox" class="wcol-store-limit" name="cart_store_limit"
				<?php
				if ( isset( $wcol_settings['cart_store_limit'] ) && 'on' === $wcol_settings['cart_store_limit'] ) {
					echo 'checked'; }
				?>
				/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-store-max-amount-limit">
					<?php esc_html_e( 'Store Total Amount Maximum Limit:(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" tabindex="0" data-tip="<?php esc_html_e( 'Store Total Amount should be less than or equal to this limit.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Store Total Amount should be less than or equal to this limit.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td>
				<input type="number" min="0" disabled name="store_max_amount_limit" value="<?php echo isset( $wcol_settings['store_max_amount_limit'] ) ? esc_html( $wcol_settings['store_max_amount_limit'] ) : ''; ?>"/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wcol-store-mox-limit">
					<?php esc_html_e( 'Store Total No of orders Maximum Limit:(Pro)', 'order-limit-for-woocommerce' ); ?>
					<span class="woocommerce-help-tip" tabindex="0" data-tip="<?php esc_html_e( 'Store Total No of orders should be less than or equal to this limit.', 'order-limit-for-woocommerce' ); ?>" aria-label="<?php esc_html_e( 'Store Total No of orders should be less than or equal to this limit.', 'order-limit-for-woocommerce' ); ?>"></span>
				</label>
			</th>
			<td>
				<input type="number" min="0" disabled name="store_max_limit" value="<?php echo isset( $wcol_settings['store_max_limit'] ) ? esc_html( $wcol_settings['store_max_limit'] ) : ''; ?>"/>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Enable Time Span (Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input disabled class='store-enable-time-limit' name="store_enable_time_limit" type="checkbox"
				<?php
				if ( isset( $wcol_settings['store_enable_time_limit'] ) && 'on' === $wcol_settings['store_enable_time_limit'] ) {
					echo 'checked';}
				?>
				/>
			</td>
		</tr>
		<tr class="store-time-span
		<?php
		if ( ! isset( $wcol_settings['store_enable_time_limit'] ) || 'on' !== $wcol_settings['store_enable_time_limit'] ) {
			echo 'wcol-hidden';}
		?>
		">
			<th><?php esc_html_e( 'Select Time Span(Pro)', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-store-time-span" name="wcol_store_time_span" disabled>
					<option value="daily"  <?php echo ( isset( $wcol_settings['wcol_store_time_span'] ) && 'daily' === $wcol_settings['wcol_store_time_span'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Daily', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="weekly"  <?php echo ( isset( $wcol_settings['wcol_store_time_span'] ) && 'weekly' === $wcol_settings['wcol_store_time_span'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Weekly', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="monthly"  <?php echo ( isset( $wcol_settings['wcol_store_time_span'] ) && 'monthly' === $wcol_settings['wcol_store_time_span'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Monthly', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="yearly"  <?php echo ( isset( $wcol_settings['wcol_store_time_span'] ) && 'yearly' === $wcol_settings['wcol_store_time_span'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Yearly', 'order-limit-for-woocommerce' ); ?>
					</option>
					<option value="custom"  <?php echo ( isset( $wcol_settings['wcol_store_time_span'] ) && 'custom' === $wcol_settings['wcol_store_time_span'] ) ? 'selected' : ''; ?>>
						<?php esc_html_e( 'Custom', 'order-limit-for-woocommerce' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr class="store-time-yearly-span <?php echo ( ! isset( $wcol_settings['wcol_store_time_span'] ) || 'yearly' !== $wcol_settings['wcol_store_time_span'] ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Year Start Date', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<input type="number" max="31" min="01" class="wcol-store-yearly-start-day" name="wcol_store_yearly_start_day" value="<?php echo isset( $wcol_settings['wcol_store_yearly_start_day'] ) ? esc_html( $wcol_settings['wcol_store_yearly_start_day'] ) : '1'; ?>"/>
				<select class="wcol-store-yearly-start-month" name="wcol_store_yearly_start_month">
					<?php
					if ( is_array( $months_array ) ) {
						foreach ( $months_array as $key => $value ) {
							?>
							<option value="<?php echo esc_html( $key ); ?>" <?php echo ( isset( $wcol_settings['wcol_store_yearly_start_month'] ) && $wcol_settings['wcol_store_yearly_start_month'] === $key ) ? 'selected' : ''; ?> >
								<?php echo esc_html( $value ); ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="store-time-weekly-span <?php echo ( ! isset( $wcol_settings['wcol_store_time_span'] ) || 'weekly' !== $wcol_settings['wcol_store_time_span'] ) ? 'wcol-hidden' : ''; ?> ">
			<th><?php esc_html_e( 'Week Start Day', 'order-limit-for-woocommerce' ); ?>:</th>
			<td>
				<select class="wcol-store-weekly-start-day" name="wcol_store_weekly_start_day">
					<?php
					if ( is_array( $weeks_array ) ) {
						foreach ( $weeks_array as $key => $value ) {
							?>
							<option value="<?php echo esc_html( $key ); ?>" <?php echo ( isset( $wcol_settings['wcol_store_weekly_start_day'] ) && $wcol_settings['wcol_store_weekly_start_day'] === $key ) ? 'selected' : ''; ?> >
								<?php echo esc_html( $value ); ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="store-time-monthly-span <?php echo ( ! isset( $wcol_settings['wcol_store_time_span'] ) || 'monthly' !== $wcol_settings['wcol_store_time_span'] ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Month Start Date', 'order-limit-for-woocommerce' ); ?>:</th>
			<td><input class="wcol-store-monthly-start-date" type="number" min="01" max="31" name="wcol_store_monthly_start_date" value="<?php echo isset( $wcol_settings['wcol_store_monthly_start_date'] ) ? esc_html( $wcol_settings['wcol_store_monthly_start_date'] ) : '1'; ?>"/></td>
		</tr>
		<tr class="store-time-custom-span <?php echo ( ! isset( $wcol_settings['wcol_store_time_span'] ) || 'custom' !== $wcol_settings['wcol_store_time_span'] ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'Start Date', 'order-limit-for-woocommerce' ); ?>:</th>
			<td><input class="wcol-store-start-time " name="wcol_store_start_time" value="<?php echo isset( $wcol_settings['wcol_store_start_time'] ) ? esc_html( $wcol_settings['wcol_store_start_time'] ) : ''; ?>"/></td>
		</tr>

		<tr class="store-time-custom-span <?php echo ( ! isset( $wcol_settings['wcol_store_time_span'] ) || 'custom' !== $wcol_settings['wcol_store_time_span'] ) ? 'wcol-hidden' : ''; ?>">
			<th><?php esc_html_e( 'End Date', 'order-limit-for-woocommerce' ); ?>:</th>
			<td><input class="wcol-store-end-time" name="wcol_store_end_time" value="<?php echo isset( $wcol_settings['wcol_store_end_time'] ) ? esc_html( $wcol_settings['wcol_store_end_time'] ) : ''; ?>"/></td>
		</tr>
	</tbody>
</table>
