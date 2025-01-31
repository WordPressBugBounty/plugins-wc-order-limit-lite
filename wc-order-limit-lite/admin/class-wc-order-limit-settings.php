<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin
 */

// Class to define the custom settings page with sub-tabs.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'WC_Order_Limit_Settings' ) ) {
	/**
	 * Add setting in woocommerce setting page.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    WC_Order_Limit
	 * @subpackage WC_Order_Limit/admin
	 * @author     xfinitysoft <support@xfinitysoft.com>
	 */
	class WC_Order_Limit_Settings extends WC_Settings_Page {
		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    5.4.0
		 */
		public function __construct() {
			$this->id    = 'wcol_settings';
			$this->label = 'Order Limit Settings';
			parent::__construct();
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'wcol_add_settings_tab' ), 50 );
			add_action( 'woocommerce_sections_wcol_settings', array( $this, 'wcol_add_setting_tabs' ) );
			add_action( 'woocommerce_settings_wcol_settings', array( $this, 'wcol_add_setting_fields' ) );
			add_action( 'woocommerce_settings_save_wcol_settings', array( $this, 'wcol_settings_save' ) );
		}
		/**
		 * Initialize Setting tab id.
		 *
		 * @since    5.4.0
		 */
		public function get_id() {
			return $this->id;
		}
		/**
		 * Initialize Setting tabs.
		 *
		 * @since    5.4.0
		 *
		 * @param array $settings_tabs Array of setting tabs.
		 */
		public function wcol_add_settings_tab( $settings_tabs ) {
			$settings_tabs['wcol_settings'] = __( 'Order Limit Settings', 'order-limit-for-woocommerce' );
			return $settings_tabs;
		}
		/**
		 *  Add custom with order limit settings
		 *
		 * @since    5.4.0
		 */
		public function wcol_add_setting_tabs() {
			global $current_section;
			$sections = array(
				''            => 'General',
				'store-limit' => 'Store Limit',
				'order-total' => 'Order Total',
				'shortcode'   => 'Shortcode',
			);
			echo '<ul class="subsubsub">';

			foreach ( $sections as $id => $label ) {
				$url       = add_query_arg(
					array(
						'page'    => 'wc-settings',
						'tab'     => 'wcol_settings',
						'section' => $id,
					),
					admin_url( 'admin.php' )
				);
				$current   = $current_section === $id ? 'current' : '';
				$keys      = array_keys( $sections );
				$separator = end( $keys ) === $id ? '' : '|';
				?>
			<li>
				<a href="<?php echo esc_url( wp_nonce_url( $url, 'wcol_setting', 'wcol_setting_nonce' ) ); ?>" class="<?php echo esc_attr( $current ); ?>">
				<?php echo esc_html( $label ); ?>
				</a> 
				<?php echo esc_html( $separator ); ?> 
			</li>
				<?php
			}

			echo '</ul><br class="clear" />';
		}
		/**
		 *  Add field with order limit settings
		 *
		 * @since    5.4.0
		 */
		public function wcol_add_setting_fields() {
			$current_section = '';
			if ( isset( $_GET['wcol_setting_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['wcol_setting_nonce'] ) ), 'wcol_setting' ) ) {
				$current_section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';
			}
			$wcol_options  = get_option( 'wcol_options' );
			$wcol_settings = $wcol_options['wcol_settings'];
			wp_nonce_field( 'wcol_setting_save', '_wcol_setting_nonce' );
			$weeks_array  = array(
				'monday'    => esc_html__( 'Monday', 'order-limit-for-woocommerce' ),
				'tuesday'   => esc_html__( 'Tuesday', 'order-limit-for-woocommerce' ),
				'wednesday' => esc_html__( 'Wednesday', 'order-limit-for-woocommerce' ),
				'thursday'  => esc_html__( 'Thursday', 'order-limit-for-woocommerce' ),
				'friday'    => esc_html__( 'Friday', 'order-limit-for-woocommerce' ),
				'saturday'  => esc_html__( 'Saturday', 'order-limit-for-woocommerce' ),
				'sunday'    => esc_html__( 'Sunday', 'order-limit-for-woocommerce' ),
			);
			$months_array = array(
				'01' => __( 'Jan', 'order-limit-for-woocommerce' ),
				'02' => __( 'Feb', 'order-limit-for-woocommerce' ),
				'03' => __( 'Mar', 'order-limit-for-woocommerce' ),
				'04' => __( 'Apr', 'order-limit-for-woocommerce' ),
				'05' => __( 'May', 'order-limit-for-woocommerce' ),
				'06' => __( 'Jun', 'order-limit-for-woocommerce' ),
				'07' => __( 'Jul', 'order-limit-for-woocommerce' ),
				'08' => __( 'Aug', 'order-limit-for-woocommerce' ),
				'09' => __( 'Sep', 'order-limit-for-woocommerce' ),
				'10' => __( 'Oct', 'order-limit-for-woocommerce' ),
				'11' => __( 'Nov', 'order-limit-for-woocommerce' ),
				'12' => __( 'Dec', 'order-limit-for-woocommerce' ),
			);
			switch ( $current_section ) {
				case 'store-limit':
					include plugin_dir_path( __FILE__ ) . 'views/wc-order-limit-store-setting.php';
					break;
				case 'order-total':
					include plugin_dir_path( __FILE__ ) . 'views/wc-order-limit-order-total-setting.php';
					break;
				case 'shortcode':
					include plugin_dir_path( __FILE__ ) . 'views/wc-order-limit-shortcodes.php';
					break;
				default:
					include plugin_dir_path( __FILE__ ) . 'views/wc-order-limit-settings.php';
					break;
			}
		}

		/**
		 *  Save settings
		 *
		 * @since    5.4.0
		 */
		public function wcol_settings_save() {
			$wcol_options  = get_option( 'wcol_options' );
			$wcol_settings = $wcol_options['wcol_settings'];
			if ( ! isset( $_REQUEST['_wcol_setting_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wcol_setting_nonce'] ) ), 'wcol_setting_save' ) ) {
				return;
			}
			if ( isset( $_REQUEST['store_limit_settings'] ) ) {
				$wcol_settings['cart_store_limit']              = isset( $_REQUEST['cart_store_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cart_store_limit'] ) ) : '';
				$wcol_settings['store_max_amount_limit']        = isset( $_REQUEST['store_max_amount_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['store_max_amount_limit'] ) ) : '';
				$wcol_settings['store_max_limit']               = isset( $_REQUEST['store_max_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['store_max_limit'] ) ) : '';
				$wcol_settings['store_enable_time_limit']       = isset( $_REQUEST['store_enable_time_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['store_enable_time_limit'] ) ) : '';
				$wcol_settings['wcol_store_time_span']          = isset( $_REQUEST['wcol_store_time_span'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wcol_store_time_span'] ) ) : '';
				$wcol_settings['wcol_store_yearly_start_month'] = isset( $_REQUEST['wcol_store_yearly_start_month'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wcol_store_yearly_start_month'] ) ) : '';
				$wcol_settings['wcol_store_weekly_start_day']   = isset( $_REQUEST['wcol_store_weekly_start_day'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wcol_store_weekly_start_day'] ) ) : '';
				$wcol_settings['wcol_store_monthly_start_date'] = isset( $_REQUEST['wcol_store_monthly_start_date'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wcol_store_monthly_start_date'] ) ) : '';
				$wcol_settings['wcol_store_start_time']         = isset( $_REQUEST['wcol_store_start_time'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wcol_store_start_time'] ) ) : '';
				$wcol_settings['wcol_store_end_time']           = isset( $_REQUEST['wcol_store_end_time'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wcol_store_end_time'] ) ) : '';
			}
			if ( isset( $_REQUEST['order_total_limit_settings'] ) ) {
				$wcol_settings['cart_total_minimum_limit']           = isset( $_REQUEST['cart_total_minimum_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cart_total_minimum_limit'] ) ) : '';
				$wcol_settings['cart_total_enable_single_cat_limit'] = isset( $_REQUEST['cart_total_enable_single_cat_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cart_total_enable_single_cat_limit'] ) ) : '';
				$wcol_settings['cart_total_enable_maximum_limit']    = isset( $_REQUEST['cart_total_enable_maximum_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cart_total_enable_maximum_limit'] ) ) : '';
				$wcol_settings['cart_total_maximum_limit']           = isset( $_REQUEST['cart_total_maximum_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cart_total_maximum_limit'] ) ) : '';
				$wcol_settings['cart_total_applied_on']              = isset( $_REQUEST['cart_total_applied_on'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cart_total_applied_on'] ) ) : '';
			}
			if ( isset( $_REQUEST['wcol_general_settings'] ) ) {
				$wcol_settings['enable_store_limit']                             = isset( $_REQUEST['enable_store_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_store_limit'] ) ) : '';
				$wcol_settings['store_limit_message']                            = isset( $_REQUEST['store_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['store_limit_message'] ) ) : '';
				$wcol_settings['store_message_total_amount']                     = isset( $_REQUEST['store_message_total_amount'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['store_message_total_amount'] ) ) : '';
				$wcol_settings['enable_product_limit']                           = isset( $_REQUEST['enable_product_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_product_limit'] ) ) : '';
				$wcol_settings['product_limit_message']                          = isset( $_REQUEST['product_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['product_limit_message'] ) ) : '';
				$wcol_settings['parent_product_limit_message']                   = isset( $_REQUEST['parent_product_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['parent_product_limit_message'] ) ) : '';
				$wcol_settings['shortcode_product_limit_message']                = isset( $_REQUEST['shortcode_product_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['shortcode_product_limit_message'] ) ) : '';
				$wcol_settings['product_limit_message_across_all_orders']        = isset( $_REQUEST['product_limit_message_across_all_orders'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['product_limit_message_across_all_orders'] ) ) : '';
				$wcol_settings['shortcode_product_limit_message_orders']         = isset( $_REQUEST['shortcode_product_limit_message_orders'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['shortcode_product_limit_message_orders'] ) ) : '';
				$wcol_settings['product_limit_message_across_all_users_orders']  = isset( $_REQUEST['product_limit_message_across_all_users_orders'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['product_limit_message_across_all_users_orders'] ) ) : '';
				$wcol_settings['product_limit_message_accomulative']             = isset( $_REQUEST['product_limit_message_accomulative'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['product_limit_message_accomulative'] ) ) : '';
				$wcol_settings['enable_category_limit']                          = isset( $_REQUEST['enable_category_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_category_limit'] ) ) : '';
				$wcol_settings['category_limit_message']                         = isset( $_REQUEST['category_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['category_limit_message'] ) ) : '';
				$wcol_settings['parent_category_limit_message']                  = isset( $_REQUEST['parent_category_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['parent_category_limit_message'] ) ) : '';
				$wcol_settings['shortcode_category_limit_message']               = isset( $_REQUEST['shortcode_category_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['shortcode_category_limit_message'] ) ) : '';
				$wcol_settings['category_limit_message_across_all_orders']       = isset( $_REQUEST['category_limit_message_across_all_orders'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['category_limit_message_across_all_orders'] ) ) : '';
				$wcol_settings['shortcode_category_limit_message_orders']        = isset( $_REQUEST['shortcode_category_limit_message_orders'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['shortcode_category_limit_message_orders'] ) ) : '';
				$wcol_settings['category_limit_message_across_all_users_orders'] = isset( $_REQUEST['category_limit_message_across_all_users_orders'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['category_limit_message_across_all_users_orders'] ) ) : '';
				$wcol_settings['category_limit_message_accomulative']            = isset( $_REQUEST['category_limit_message_accomulative'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['category_limit_message_accomulative'] ) ) : '';
				$wcol_settings['enable_vendor_limit']                            = isset( $_REQUEST['enable_vendor_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_vendor_limit'] ) ) : '';
				$wcol_settings['vendor_limit_message']                           = isset( $_REQUEST['vendor_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['vendor_limit_message'] ) ) : '';
				$wcol_settings['enable_customer_limit']                          = isset( $_REQUEST['enable_customer_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_customer_limit'] ) ) : '';
				$wcol_settings['monthly_limit_reset_date']                       = isset( $_REQUEST['monthly_limit_reset_date'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['monthly_limit_reset_date'] ) ) : '';
				$wcol_settings['weekly_limit_reset_date']                        = isset( $_REQUEST['weekly_limit_reset_date'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['weekly_limit_reset_date'] ) ) : '';
				$wcol_settings['customer_message']                               = isset( $_REQUEST['customer_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['customer_message'] ) ) : '';
				$wcol_settings['customer_message_total_amount']                  = isset( $_REQUEST['customer_message_total_amount'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['customer_message_total_amount'] ) ) : '';
				$wcol_settings['shortcode_customer_message']                     = isset( $_REQUEST['shortcode_customer_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['shortcode_customer_message'] ) ) : '';
				$wcol_settings['shortcode_customer_message_total_amount']        = isset( $_REQUEST['shortcode_customer_message_total_amount'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['shortcode_customer_message_total_amount'] ) ) : '';
				$wcol_settings['enable_cart_total_limit']                        = isset( $_REQUEST['enable_cart_total_limit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_cart_total_limit'] ) ) : '';
				$wcol_settings['cart_total_limit_message']                       = isset( $_REQUEST['cart_total_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cart_total_limit_message'] ) ) : '';
				$wcol_settings['cart_single_cat_limit_message']                  = isset( $_REQUEST['cart_single_cat_limit_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cart_single_cat_limit_message'] ) ) : '';
				$wcol_settings['enable_checkout_button']                         = isset( $_REQUEST['enable_checkout_button'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_checkout_button'] ) ) : '';
				$wcol_settings['enable_checkout_redirect']                       = isset( $_REQUEST['enable_checkout_redirect'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_checkout_redirect'] ) ) : '';
				$wcol_settings['reset_time']                                     = isset( $_REQUEST['reset_time'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['reset_time'] ) ) : '';
			}
			$wcol_options['wcol_settings'] = $wcol_settings;
			update_option( 'wcol_options', $wcol_options );
		}
	}
	return new WC_Order_Limit_Settings();
}
