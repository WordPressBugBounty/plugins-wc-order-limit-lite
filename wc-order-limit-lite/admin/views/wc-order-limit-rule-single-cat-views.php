<?php
/**
 *
 * Single category rule page of plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin/views
 */

$installed_payment_methods = WC()->payment_gateways()->payment_gateways();
?>
<tr class="form-field xswcol-rules">
	<td colspan="2">
		<h2> 
			<?php esc_html_e( 'WC Order Limit Rules', 'order-limit-for-woocommerce' ); ?> 
			<a class="xs-pro-link xs-button-main" href="https://woocommerce.com/products/order-limit/" target="_blank">
				<input type="button" name="xs-button" id="xs-button" class="button" value="<?php esc_html_e( 'Pro Version', 'order-limit-for-woocommerce' ); ?>">
			</a>
		</h2>
		<?php wp_nonce_field( 'wcol_save_rules', '_wcol_save_rules_nonce', true ); ?>
		<div class="">
			<span class="spinner wcol_spinner"></span>
			<a id="wcol_add_new_rule" data-id='category' class="button" href="#"><?php esc_html_e( 'Add New Rule', 'order-limit-for-woocommerce' ); ?></a>
		</div>
		<div class="clear"></div>
		<div class="wcol_single_cat_rules">
			<?php
			$xs_i = 0;
			if ( ! empty( $wcol_rules ) && is_array( $wcol_rules ) ) {
				foreach ( $wcol_rules as $mkey => $wcol_options ) {
					$max_limit = esc_html( $wcol_options['max-rule-limit'] );
					$min_limit = esc_html( $wcol_options['min-rule-limit'] );
					if ( empty( $max_limit ) || 'on' !== $wcol_options['enable-max-rule-limit'] ) {
						$max_limit = '<span style="font-size:20px; font-weight:bold;vertical-align: text-bottom;">∞</span>';
					} elseif ( 'amount' === $wcol_options['applied_on'] ) {
							$max_limit = wc_price( $max_limit );
					}
					if ( 'amount' === $wcol_options['applied_on'] ) {
						$min_limit = wc_price( $min_limit );
					}
					?>
			<div class="wcol_single_cat_rule <?php echo ( isset( $wcol_options['accomulative'] ) && 'on' === $wcol_options['accomulative'] ) ? 'wcol_accomulative_rule' : ''; ?>">
				<h3 class="wcol_cat_accordion">
					<?php
					$allowed_html = array(
						'span' => array(),
						'bdi'  => array(),
					);
					if ( 'on' === $wcol_options['enable-for-users'] ) {
						switch ( $wcol_options['user-type'] ) {
							case 'guest-users':
								echo esc_html__( 'Rule for guest users', 'order-limit-for-woocommerce' ) . ' - ' . wp_kses( $min_limit, $allowed_html ) . ' - ' . wp_kses( $max_limit, $allowed_html );
								break;
							case 'specific-users':
								echo esc_html__( 'Rule for specific users', 'order-limit-for-woocommerce' ) . ' - ' . wp_kses( $min_limit, $allowed_html ) . ' - ' . wp_kses( $max_limit, $allowed_html );
								break;
							case 'specific-roles':
								echo esc_html__( 'Rule for specific roles', 'order-limit-for-woocommerce' ) . ' - ' . wp_kses( $min_limit, $allowed_html ) . ' - ' . wp_kses( $max_limit, $allowed_html );
								break;
							case 'all-users':
								echo esc_html__( 'Rule for All users', 'order-limit-for-woocommerce' ) . ' - ' . wp_kses( $min_limit, $allowed_html ) . ' - ' . wp_kses( $max_limit, $allowed_html );
								break;
							default:
								echo esc_html__( 'Rule ', 'order-limit-for-woocommerce' ) . ' - ' . wp_kses( $min_limit, $allowed_html ) . ' - ' . wp_kses( $max_limit, $allowed_html );
								break;
						}
					} else {
						echo esc_html__( 'Rule for all users', 'order-limit-for-woocommerce' ) . ' - ' . wp_kses( $min_limit, $allowed_html ) . ' - ' . wp_kses( $max_limit, $allowed_html );
					}
					?>
					<span class="wcol-delete" data-id="<?php echo esc_attr( $wcol_options['id'] ); ?>"><?php esc_html_e( 'Delete', 'order-limit-for-woocommerce' ); ?></span>
				</h3>
				<div class="wcol_cat_panel">
					<input type="hidden" value="<?php echo esc_attr( $wcol_options['id'] ); ?>" name="wcol_rules[id][<?php echo esc_html( $mkey ); ?>]"/>
					<?php if ( isset( $wcol_options['accomulative'] ) && 'on' === $wcol_options['accomulative'] ) { ?>
					<input type="hidden" class="wcol-loop-checkbox-hidden wcol_accomulative" value="<?php echo esc_attr( $wcol_options['accomulative'] ); ?>" name="wcol_rules[accomulative][<?php echo esc_html( $mkey ); ?>]"/>
					<div class="options_group">
						<p class="form-field">
							<label><?php esc_html_e( 'Edit This Rule:', 'order-limit-for-woocommerce' ); ?></label>
							<span class="wcol-help-tip" style="float:none;">
								<span class="wcol-tip" > <?php esc_html_e( "This Product is included in a Rule that is being applied accomulatively with other products so if you edit this products's limit options then it will be excluded from that accomulative rule.", 'order-limit-for-woocommerce' ); ?> </span>
							</span>
							<input type="checkbox" class="wcol_edit_rule" />
						</p>
					</div>
					<?php } else { ?>
					<input type="hidden" class="wcol_accomulative" value="<?php echo isset( $wcol_options['accomulative'] ) ? esc_attr( $wcol_options['accomulative'] ) : ''; ?>" name="wcol_rules[accomulative][<?php echo esc_html( $mkey ); ?>]"/>
					<?php } ?>
					<div class="options_group">
						<p class="form-field">
							<label><?php esc_html_e( 'Disable:', 'order-limit-for-woocommerce' ); ?></label>
							<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol_rules[disable-limit][<?php echo esc_html( $mkey ); ?>]" value="<?php echo esc_html( $wcol_options['disable-limit'] ); ?>"/>
							<input class="wcol-disable-rule-limit wcol-loop-checkbox" type="checkbox" <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'checked' : ''; ?> />
						</p>
						<p class="wcol-description"><?php esc_html_e( 'Leave blank for no limit.', 'order-limit-for-woocommerce' ); ?></p>
					</div>
					<div class="options_group">
						<p class="form-field">
							<label><?php esc_html_e( 'Minimum Order:', 'order-limit-for-woocommerce' ); ?></label>
							<input type="number" min="0"  class="wcol-rule-min-limit <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[min-rule-limit][<?php echo esc_html( $mkey ); ?>]" value="<?php echo isset( $wcol_options['min-rule-limit'] ) ? esc_attr( $wcol_options['min-rule-limit'] ) : ''; ?>" placeholder="<?php esc_html_e( 'Enter Minimum Order Limit', 'order-limit-for-woocommerce' ); ?>" />
						</p>
						<p class="wcol-description"><?php esc_html_e( 'Leave blank for no limit.', 'order-limit-for-woocommerce' ); ?></p>
					</div>
					<div class="options_group">
						<p class="form-field">
							<label><?php esc_html_e( 'Check Previous Orders?', 'order-limit-for-woocommerce' ); ?></label>
							<select class="across-all-orders-limit <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[across-all-orders][<?php echo esc_html( $mkey ); ?>]">
								<option value="1" <?php echo ( isset( $wcol_options['across-all-orders'] ) && '1' === $wcol_options['across-all-orders'] ) ? 'selected="selected"' : ''; ?>>
									<?php esc_html_e( 'None', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="2" <?php echo ( isset( $wcol_options['across-all-orders'] ) && '2' === $wcol_options['across-all-orders'] ) ? 'selected="selected"' : ''; ?> disabled>
									<?php esc_html_e( 'Previous Order of Current User (Pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="3" <?php echo ( isset( $wcol_options['across-all-orders'] ) && '3' === $wcol_options['across-all-orders'] ) ? 'selected="selected"' : ''; ?> disabled>
									<?php esc_html_e( 'Previous Order of All Users ( Pro )', 'order-limit-for-woocommerce' ); ?>
								</option>
							</select>
						</p>
						<p class="wcol-description"><?php esc_html_e( 'Min Limits will be ignored if this feature is enabled.', 'order-limit-for-woocommerce' ); ?></p>
					</div>
					<div class="options_group">
						<p class="form-field">
							<label><?php esc_html_e( 'Enable Maximum Limit:', 'order-limit-for-woocommerce' ); ?></label>
							<input type="hidden" class="enable-max-rule-limit-hidden wcol-loop-checkbox-hidden" value="<?php echo esc_attr( $wcol_options['enable-max-rule-limit'] ); ?>" name="wcol_rules[enable-max-rule-limit][<?php echo esc_html( $mkey ); ?>]"/>
							<input type="checkbox" class="wcol-loop-checkbox enable-max-rule-limit <?php echo ( '1' !== $wcol_options['across-all-orders'] || 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" title="<?php ( isset( $wcol_options['across-all-orders'] ) && 'on' === $wcol_options['across-all-orders'] ) ? esc_html_e( 'Previous Orders Option is Enabled', 'order-limit-for-woocommerce' ) : ''; ?>" min="0" <?php echo ( isset( $wcol_options['enable-max-rule-limit'] ) && $wcol_options['enable-max-rule-limit'] ) ? 'checked' : ''; ?>/>
						</p>
						<p class="form-field <?php echo ( 'on' !== $wcol_options['enable-max-rule-limit'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Maximum Order:', 'order-limit-for-woocommerce' ); ?></label>
							<input type="number" class="wcol-rule-max-limit <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" min="0" name="wcol_rules[max-rule-limit][<?php echo esc_html( $mkey ); ?>]" value="<?php echo isset( $wcol_options['max-rule-limit'] ) ? esc_html( $wcol_options['max-rule-limit'] ) : ''; ?>" placeholder="<?php esc_html_e( 'Enter Maximum Order Limit', 'order-limit-for-woocommerce' ); ?>" />
						</p>
					</div>
					<div class="options_group">
						<p class="form-field">
							<label><?php esc_html_e( 'Enable Time Span:', 'order-limit-for-woocommerce' ); ?></label>
							<input type="hidden" class="enable-time-limit-hidden wcol-loop-checkbox-hidden <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" value="<?php echo esc_attr( $wcol_options['enable-time-limit'] ); ?>" name="wcol_rules[enable-time-limit][<?php echo esc_html( $mkey ); ?>]"/>
							<input class="wcol-loop-checkbox enable-time-limit <?php echo ( '1' !== $wcol_options['across-all-orders'] || 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" title="<?php ( 'on' === $wcol_options['across-all-orders'] ) ? esc_html_e( 'Previous Orders Option is Enabled', 'order-limit-for-woocommerce' ) : ''; ?>" type="checkbox" <?php echo ( isset( $wcol_options['enable-time-limit'] ) && $wcol_options['enable-time-limit'] ) ? 'checked' : ''; ?> />
						</p>
						<p class="form-field <?php echo ( 'on' !== $wcol_options['enable-time-limit'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Time Span:', 'order-limit-for-woocommerce' ); ?></label>
							<select class="wcol-rule-time-span <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[rule-time-span][<?php echo esc_html( $mkey ); ?>]">
								<option value="daily"  <?php echo ( isset( $wcol_options['rule-time-span'] ) && 'daily' === $wcol_options['rule-time-span'] ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Daily', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="weekly"  <?php echo ( isset( $wcol_options['rule-time-span'] ) && 'weekly' === $wcol_options['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
									<?php esc_html_e( 'Weekly(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="monthly"  <?php echo ( isset( $wcol_options['rule-time-span'] ) && 'monthly' === $wcol_options['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
									<?php esc_html_e( 'Monthly(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="yearly"  <?php echo ( isset( $wcol_options['rule-time-span'] ) && 'yearly' === $wcol_options['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
									<?php esc_html_e( 'Yearly(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="days"  <?php echo ( isset( $wcol_options['rule-time-span'] ) && 'days' === $wcol_options['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
									<?php esc_html_e( 'Custom days(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="custom"  <?php echo ( isset( $wcol_options['rule-time-span'] ) && 'custom' === $wcol_options['rule-time-span'] ) ? 'selected' : ''; ?> disabled>
									<?php esc_html_e( 'Custom dates(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
							</select>
						</p>
						<p class="form-field <?php echo ( 'yearly' !== $wcol_options['rule-time-span'] ) ? 'wcol-hidden' : ''; ?>" >
							<label><?php esc_html_e( 'Year Start Date:(pro)', 'order-limit-for-woocommerce' ); ?></label>
							<input type="number" max="31" min="01" class="wcol-yearly-start-day <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[yearly-start-day][<?php echo esc_html( $mkey ); ?>]" value="<?php echo esc_html( $wcol_options['yearly-start-day'] ); ?>" disabled/>
							<select class="wcol-yearly-start-month <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[yearly-start-month][<?php echo esc_html( $mkey ); ?>]" disabled>
								<?php
								if ( is_array( $months_array ) ) {
									foreach ( $months_array as $key => $value ) {
										?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php echo ( $wcol_options['yearly-start-day'] === $key ) ? 'selected' : ''; ?>>
											<?php echo esc_html( $value ); ?>
										</option>
										<?php
									}
								}
								?>
							</select>
						</p>
						<p class="form-field <?php echo ( 'weekly' !== $wcol_options['rule-time-span'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Week Start Day:(Pro)', 'order-limit-for-woocommerce' ); ?></label>
							<select class="wcol-weekly-start-day <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[weekly-start-day][<?php echo esc_html( $mkey ); ?>]" disabled>
								<?php
								if ( is_array( $weeks_array ) ) {
									foreach ( $weeks_array as $key => $value ) {
										?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php echo ( $wcol_options['weekly-start-day'] === $key ) ? 'selected' : ''; ?>>
											<?php echo esc_html( $value ); ?>
										</option>
										<?php
									}
								}
								?>
							</select>
						</p>
						<p class="form-field <?php echo ( 'monthly' !== $wcol_options['rule-time-span'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Month Start Date:(Pro)', 'order-limit-for-woocommerce' ); ?></label>
							<input class="wcol-monthly-start-date <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" type="number" min="01" max="31" name="wcol_rules[monthly-start-date][<?php echo esc_html( $mkey ); ?>]" disabled value="<?php echo esc_html( $wcol_options['monthly-start-date'] ); ?>"/>
						</p>
						<p class="form-field <?php echo ( 'days' !== $wcol_options['rule-time-span'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Number of days:( Pro )', 'order-limit-for-woocommerce' ); ?></label>
							<input class="wcol-rule-days <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" type="number" min="0" name="wcol_rules[days][<?php echo esc_html( $mkey ); ?>]" value="<?php echo esc_html( $wcol_options['days'] ); ?>" disabled/>
						</p>
						<p class="form-field <?php echo ( 'custom' !== $wcol_options['rule-time-span'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Start Time: (Pro)', 'order-limit-for-woocommerce' ); ?></label>
							<input type="text" class="wcol-rule-start-time <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[rule-start-time][<?php echo esc_html( $mkey ); ?>]" value="<?php echo isset( $wcol_options['rule-start-time'] ) ? esc_html( $wcol_options['rule-start-time'] ) : ''; ?>" disabled/>
						</p>
						<p class="form-field <?php echo ( 'custom' !== $wcol_options['rule-time-span'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'End Time:(Pro)', 'order-limit-for-woocommerce' ); ?></label>
							<input type="text" class="wcol-rule-end-time <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[rule-end-time][<?php echo esc_html( $mkey ); ?>]" value="<?php echo isset( $wcol_options['rule-end-time'] ) ? esc_html( $wcol_options['rule-end-time'] ) : ''; ?>" disabled/>
						</p>
					</div>
					<div class="options_group">                                                                        
						<p class="form-field">
							<label><?php esc_html_e( 'Applied on:', 'order-limit-for-woocommerce' ); ?></label>
							<select class="wcol-applied-on <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[applied_on][<?php echo esc_html( $mkey ); ?>]" >
								<option value="amount" <?php echo ( isset( $wcol_options['applied_on'] ) && 'amount' === $wcol_options['applied_on'] ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Amount', 'order-limit-for-woocommerce' ); ?> 
								</option>
								<option value="quantity" <?php echo ( isset( $wcol_options['applied_on'] ) && 'quantity' === $wcol_options['applied_on'] ) ? 'selected' : ''; ?>> 
									<?php esc_html_e( 'Quantity', 'order-limit-for-woocommerce' ); ?> 
								</option>
							</select>
						</p>
						<p class="wcol-description"><?php esc_html_e( 'Select if limit will be applied on quantity or amount.', 'order-limit-for-woocommerce' ); ?></p>
					</div>
					<div class="options_group">                                                                        
						<p class="form-field">
							<label><?php esc_html_e( 'Enable for Users:', 'order-limit-for-woocommerce' ); ?></label>
							<input type="hidden" class="wcol-loop-checkbox-hidden" value="<?php echo esc_attr( $wcol_options['enable-for-users'] ); ?>" name="wcol_rules[enable-for-users][<?php echo esc_html( $mkey ); ?>]"/>
							<input type="checkbox" class="wcol-loop-checkbox enable-users-limit <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" <?php echo ( isset( $wcol_options['enable-for-users'] ) && $wcol_options['enable-for-users'] ) ? 'checked' : ''; ?>/>
						</p>
						<p class="form-field <?php echo ( 'on' !== $wcol_options['enable-for-users'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'User Type:', 'order-limit-for-woocommerce' ); ?></label>
							<select class="wcol-rule-user-type <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[user-type][<?php echo esc_html( $mkey ); ?>]" >
								<option value="">
									<?php esc_html_e( 'None', 'order-limit-for-woocommerce' ); ?>
								</option>   
								<option value="guest-users" disabled <?php echo ( 'guest-users' === $wcol_options['user-type'] ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Guest Users Only(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="specific-users" disabled <?php echo ( 'specific-users' === $wcol_options['user-type'] ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Specific Users Only(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="specific-roles" disabled <?php echo ( 'specific-roles' === $wcol_options['user-type'] ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Specific Roles Only(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="all-users" disabled <?php echo ( 'all-users' === $wcol_options['user-type'] ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'All Users(pro)', 'order-limit-for-woocommerce' ); ?>
								</option>
							</select>
						</p>
						<p class="form-field  <?php echo ( 'on' !== $wcol_options['enable-for-users'] || 'guest-users' !== $wcol_options['user-type'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Guest Users:(pro)', 'order-limit-for-woocommerce' ); ?></label>
							<select  disabled class="wcol-select-guest-users wcol-rule-guest-users  <?php echo ( isset( $wcol_options['disable-limit'] ) && 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[rule-guest-users][<?php echo esc_html( $mkey ); ?>][]" multiple="multiple" >
								<option value="ip"  <?php echo ( isset( $wcol_options['rule-guest-users'] ) && in_array( 'ip', $wcol_options['rule-guest-users'], true ) ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'IP Address', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="cookies" <?php echo ( isset( $wcol_options['rule-guest-users'] ) && in_array( 'cookies', $wcol_options['rule-guest-users'], true ) ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Cookies', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="email" <?php echo ( isset( $wcol_options['rule-guest-users'] ) && in_array( 'email', $wcol_options['rule-guest-users'], true ) ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Email', 'order-limit-for-woocommerce' ); ?>
								</option>
								<option value="phone" <?php echo ( isset( $wcol_options['rule-guest-users'] ) && in_array( 'phone', $wcol_options['rule-guest-users'], true ) ) ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Phone number', 'order-limit-for-woocommerce' ); ?>
								</option>
							</select>
						</p>
						<p class="form-field <?php echo ( 'on' !== $wcol_options['enable-for-users'] || 'specific-users' !== $wcol_options['user-type'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Users:(pro)', 'order-limit-for-woocommerce' ); ?></label>
							<?php
							$user_ids = array();
							if ( isset( $wcol_options['rule-users'] ) ) {
								$user_ids = $wcol_options['rule-users'];
							}

							?>
							<select disabled class="wcol-select-users wcol-rule-users <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[rule-users][<?php echo esc_html( $mkey ); ?>][]" multiple="multiple" >
								<?php
								if ( ! empty( $user_ids ) && is_array( $user_ids ) ) {
									foreach ( $user_ids as $user_id ) {
										$user_info = get_userdata( $user_id );
										echo '<option value="' . esc_attr( $user_id ) . '" selected="selected">' . esc_html( $user_info->user_login ) . '</option>';

									}
								}
								?>
							</select>
						</p>
						<p class="form-field <?php echo ( 'on' !== $wcol_options['enable-for-users'] || 'specific-roles' !== $wcol_options['user-type'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Roles:(pro)', 'order-limit-for-woocommerce' ); ?></label>
							<?php
							$user_roles = array();
							if ( isset( $wcol_options['rule-roles'] ) ) {
								$user_roles = $wcol_options['rule-roles'];
							}
							?>
							<select disabled class="wcol-select-roles wcol-rule-roles <?php echo ( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol_rules[rule-roles][<?php echo esc_html( $mkey ); ?>][]" multiple="multiple" >
								<?php
								if ( ! empty( $user_roles ) && is_array( $user_roles ) ) {
									foreach ( $user_roles as $value ) {
										echo '<option value="' . esc_attr( $value ) . '" selected="selected">' . esc_html( ucfirst( $value ) ) . '</option>';

									}
								}
								?>
							</select>
						</p>
					</div>
					<div class="options_group">
						<p class="form-field">
							<label><?php esc_html_e( 'Enable for Payment Method:', 'order-limit-for-woocommerce' ); ?></label>
							<input type="hidden" class="wcol-loop-checkbox-hidden" value="<?php echo isset( $wcol_options['enable-for-payment'] ) ? esc_attr( $wcol_options['enable-for-payment'] ) : ''; ?>" name="wcol_rules[enable-for-payment][<?php echo esc_html( $mkey ); ?>]"/>
							<input type="checkbox" class="wcol-loop-checkbox enable-payment-limit <?php echo( 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" <?php echo ( isset( $wcol_options['enable-for-payment'] ) && $wcol_options['enable-for-payment'] ) ? 'checked' : ''; ?>/>
						</p>
						<p class="form-field wcol-payment-method <?php echo ( ! isset( $wcol_options['enable-for-payment'] ) || 'on' !== $wcol_options['enable-for-payment'] ) ? 'wcol-hidden' : ''; ?>">
							<label><?php esc_html_e( 'Payment Method(Pro)', 'order-limit-for-woocommerce' ); ?>:</label>
							<select disabled class="wcol-rule-payment-type <?php echo ( isset( $wcol_options['disable-limit'] ) && 'on' === $wcol_options['disable-limit'] ) ? 'wcol-disabled' : ''; ?>" name="wcol-rules[payment-method][<?php echo esc_html( $mkey ); ?>][]" multiple="multiple">
								<?php
								if ( is_array( $installed_payment_methods ) && ! empty( $installed_payment_methods ) ) {
									foreach ( $installed_payment_methods as $key => $method ) {
										$selected = '';
										if ( isset( $wcol_options['payment-method'] ) && in_array( $key, $wcol_options['payment-method'], true ) ) {
											$selected = 'selected="selected"';
										}
										echo "<option value='" . esc_attr( $key ) . "' " . esc_html( $selected ) . '>' . esc_html( $method->title ) . '</option>';
									}
								}
								?>
							</select>
						</p> 
					</div>
				</div>
			</div>
					<?php
					$xs_i = $mkey; }
			}
			?>
			<input type="hidden" name="xswcol-spcid" class="xswcol-spcid" value="<?php echo esc_html( $xs_i ); ?>">
		</div>	
	</td>
</tr>
