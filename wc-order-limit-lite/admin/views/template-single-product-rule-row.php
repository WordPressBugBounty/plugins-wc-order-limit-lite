<?php
/**
 *
 * Single product ajax rule page of plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin/views
 */

$spid = 0;
//phpcs:ignore
if ( isset( $_POST['wcol_spid'] ) && ! empty( $_POST['wcol_spid'] ) ) {
	//phpcs:ignore
	$spid = sanitize_text_field( wp_unslash( $_POST['wcol_spid'] ) );
}
$installed_payment_methods = WC()->payment_gateways()->payment_gateways();
?>
<div class="wc-metabox wcol-new open">
	<input type="hidden" name="wcol_rules[id][<?php esc_html( $spid ); ?>]" value="<?php echo esc_attr( uniqid() ); ?>"/>
	<h3><?php esc_html_e( 'New Rule(Not Saved)', 'order-limit-for-woocommerce' ); ?><span class="wcol-delete" data-id="<?php echo esc_attr( uniqid() ); ?>"><?php esc_html_e( 'Delete', 'order-limit-for-woocommerce' ); ?></span></h3>
	<div class="wc-metabox-content">
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Minimum Order', 'order-limit-for-woocommerce' ); ?>:</label>
				<input type="number" min="0"  name="wcol_rules[min-rule-limit][<?php esc_html( $spid ); ?>]" class="wcol-rule-min-limit" value="" placeholder="<?php esc_html_e( 'Enter Minimum Order Limit', 'order-limit-for-woocommerce' ); ?>" />
			</p>
			<p class="wcol-description"><?php esc_html_e( 'Leave blank for no limit.', 'order-limit-for-woocommerce' ); ?></p>
		</div>

		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Check Previous Orders:', 'order-limit-for-woocommerce' ); ?></label>
				<select class="across-all-orders-limit" name="wcol_rules[across-all-orders][<?php esc_html( $spid ); ?>]">
					<option value="1"><?php esc_html_e( 'None', 'order-limit-for-woocommerce' ); ?></option>
					<option value="2" disabled><?php esc_html_e( 'Previous Orders of Current User(pro)', 'order-limit-for-woocommerce' ); ?></option>
					<option value="3" disabled><?php esc_html_e( 'Previous Orders of All Users(pro)', 'order-limit-for-woocommerce' ); ?></option>
				</select>
			</p>
			<p class="wcol-description"><?php esc_html_e( 'Min Limits will be ignored if this feature is enabled.', 'order-limit-for-woocommerce' ); ?></p>
		</div>

		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Enable Maximum Limit', 'order-limit-for-woocommerce' ); ?>:</label>
				<input type="hidden" class="wcol-loop-checkbox-hidden enable-max-rule-limit-hidden" name="wcol_rules[enable-max-rule-limit][<?php esc_html( $spid ); ?>]" />
				<input type="checkbox" class="wcol-loop-checkbox enable-max-rule-limit" min="0"/>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Maximum Order', 'order-limit-for-woocommerce' ); ?>:</label>
				<input type="number" class="wcol-rule-max-limit" min="0"  name="wcol_rules[max-rule-limit][<?php esc_html( $spid ); ?>]" placeholder="<?php esc_html_e( 'Enter Maximum Order Limit', 'order-limit-for-woocommerce' ); ?>" />
			</p>
		</div>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Enable Time Span', 'order-limit-for-woocommerce' ); ?>:</label>
				<input type="hidden" class="wcol-loop-checkbox-hidden enable-time-limit-hidden" name="wcol_rules[enable-time-limit][<?php esc_html( $spid ); ?>]" />
				<input class="wcol-loop-checkbox enable-time-limit" type="checkbox" />
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Time Span:', 'order-limit-for-woocommerce' ); ?></label>
				<select class="wcol-rule-time-span" name="wcol_rules[rule-time-span][<?php esc_html( $spid ); ?>]">
				<option value="daily" ><?php esc_html_e( 'Daily', 'order-limit-for-woocommerce' ); ?></option>
					<option value="weekly(pro)" disabled ><?php esc_html_e( 'Weekly (Pro)', 'order-limit-for-woocommerce' ); ?></option>
					<option value="monthly(pro)" disabled ><?php esc_html_e( 'Monthly(Pro)', 'order-limit-for-woocommerce' ); ?></option>
					<option value="yearly(pro)" disabled ><?php esc_html_e( 'Yearly(Pro)', 'order-limit-for-woocommerce' ); ?></option>
					<option value="days(pro)" disabled > <?php esc_html_e( 'Custom Days(Pro)', 'order-limit-for-woocommerce' ); ?></option>
					<option value="custom(pro)" disabled ><?php esc_html_e( 'Custom Date(Pro)', 'order-limit-for-woocommerce' ); ?></option>
				</select>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Year Start Date:(Pro)', 'order-limit-for-woocommerce' ); ?></label>
				<input type="number" max="31" min="01" class="wcol-yearly-start-day" name="wcol_rules[yearly-start-day][<?php esc_html( $spid ); ?>]" value="01" disabled/>
				<select class="wcol-yearly-start-month" disabled name="wcol_rules[yearly-start-month][<?php esc_html( $spid ); ?>]">
					<?php
					if ( is_array( $months_array ) ) {
						foreach ( $months_array as $key => $value ) {
							?>
							<option value="<?php echo esc_attr( $key ); ?>" ><?php echo esc_html( $value ); ?></option>
							<?php
						}
					}
					?>
				</select>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Week Start Day:(Pro)', 'order-limit-for-woocommerce' ); ?></label>
				<select class="wcol-weekly-start-day" disabled name="wcol_rules[weekly-start-day][<?php esc_html( $spid ); ?>]">
					<?php
					if ( is_array( $weeks_array ) ) {
						foreach ( $weeks_array as $key => $value ) {
							?>
							<option value="<?php echo esc_attr( $key ); ?>" ><?php echo esc_html( $value ); ?></option>
							<?php
						}
					}
					?>
				</select>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Number of days:(Pro)', 'order-limit-for-woocommerce' ); ?></label>
				<input class="wcol-rule-days" type="number" disabled name="wcol_rules[days][<?php esc_html( $spid ); ?>]" value="01"/>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Month Start Date:(Pro)', 'order-limit-for-woocommerce' ); ?></label>
				<input class="wcol-monthly-start-date" disabled type="number" min="01" max="31" name="wcol_rules[monthly-start-date][<?php esc_html( $spid ); ?>]" value="01"/>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Start Time (Pro)', 'order-limit-for-woocommerce' ); ?>:</label>
				<input type="text" class="wcol-rule-start-time" disabled name="wcol_rules[rule-start-time][<?php esc_html( $spid ); ?>]" />
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'End Time(Pro)', 'order-limit-for-woocommerce' ); ?>:</label>
				<input type="text" class="wcol-rule-end-time" disabled name="wcol_rules[rule-end-time][<?php esc_html( $spid ); ?>]"/>
			</p>
		</div>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Applied on', 'order-limit-for-woocommerce' ); ?>:</label>
				<select name="wcol_rules[applied_on][<?php esc_html( $spid ); ?>]" >
					<option value="amount" ><?php esc_html_e( 'Amount', 'order-limit-for-woocommerce' ); ?></option>
					<option value="quantity" ><?php esc_html_e( 'Quantity', 'order-limit-for-woocommerce' ); ?></option>
					<?php do_action( 'wcol_applied_on_ajax_option' ); ?>
				</select>
			</p>
			<p class="wcol-description"><?php esc_html_e( 'Select if limit will be applied on quantity or amount.', 'order-limit-for-woocommerce' ); ?></p>
		</div>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Enable for Users', 'order-limit-for-woocommerce' ); ?>:</label>
				<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol_rules[enable-for-users][<?php esc_html( $spid ); ?>]" />
				<input type="checkbox" class="wcol-loop-checkbox enable-users-limit" />
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'User Type', 'order-limit-for-woocommerce' ); ?>:</label>
				<select class="wcol-rule-user-type" name="wcol_rules[user-type][<?php esc_html( $spid ); ?>]">
				<option value=""><?php esc_html_e( 'None', 'order-limit-for-woocommerce' ); ?></option>
					<option disabled value="guest-users"><?php esc_html_e( 'Guest Users Only(Pro)', 'order-limit-for-woocommerce' ); ?></option>
					<option disabled value="specific-users"><?php esc_html_e( 'Specific Users Only(Pro)', 'order-limit-for-woocommerce' ); ?></option>
					<option disabled value="specific-roles"><?php esc_html_e( 'Specific Roles Only(Pro)', 'order-limit-for-woocommerce' ); ?></option>
					<option disabled value="all-users"><?php esc_html_e( 'All Users(Pro)', 'order-limit-for-woocommerce' ); ?></option>
				</select>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Guest Users:(Pro)', 'order-limit-for-woocommerce' ); ?></label>
				<select class="wcol-select-guest-users wcol-rule-guest-users" disabled name="wcol-rules[rule-guest-users][<?php esc_html( $spid ); ?>][]" multiple="multiple" >
					<option value="ip"><?php esc_html_e( 'IP Address', 'order-limit-for-woocommerce' ); ?></option>
					<option value="cookies"><?php esc_html_e( 'Cookies', 'order-limit-for-woocommerce' ); ?></option>
					<option value="email"><?php esc_html_e( 'Email', 'order-limit-for-woocommerce' ); ?></option>
					<option value="phone"><?php esc_html_e( 'Phone number', 'order-limit-for-woocommerce' ); ?></option>
				</select>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Users(Pro)', 'order-limit-for-woocommerce' ); ?>:</label>
				<select class="wcol-select-users wcol-rule-users" disabled name="wcol_rules[rule-users][<?php esc_html( $spid ); ?>][]"></select>
			</p>
			<p class="form-field wcol-hidden">
				<label><?php esc_html_e( 'Roles(Pro)', 'order-limit-for-woocommerce' ); ?>:</label>
				<select class="wcol-select-roles wcol-rule-roles" disabled name="wcol_rules[rule-roles][<?php esc_html( $spid ); ?>][]"></select>
			</p>
		</div>
		<div class="options_group">
			<p class="form-field">
				<label><?php esc_html_e( 'Enable for Payment Method:', 'order-limit-for-woocommerce' ); ?></label>
				<input type="hidden" class="wcol-loop-checkbox-hidden"  name="wcol_rules[enable-for-payment][<?php esc_html( $spid ); ?>]"/>
				<input type="checkbox" class="wcol-loop-checkbox enable-payment-limit" />
			</p>
			<p class="form-field wcol-payment-method wcol-hidden">
				<label><?php esc_html_e( 'Payment Method(Pro)', 'order-limit-for-woocommerce' ); ?>:</label>
				<select class="wcol-rule-payment-type" disabled name="wcol-rules[payment-method][<?php esc_html( $spid ); ?>][]" multiple="multiple">
					<?php
					if ( is_array( $installed_payment_methods ) && ! empty( $installed_payment_methods ) ) {
						foreach ( $installed_payment_methods as $key => $method ) {
							echo "<option value='" . esc_attr( $key ) . "'>" . esc_html( $method->title ) . '</option>';
						}
					}
					?>
				</select>
			</p>
		</div>
	</div>
</div>
