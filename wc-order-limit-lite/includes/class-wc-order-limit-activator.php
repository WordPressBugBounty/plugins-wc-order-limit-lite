<?php
/**
 * Fired during plugin activation
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      3.0.0
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/includes
 * @author     xfinitysoft <support@xfinitysoft.com>
 */
class WC_Order_Limit_Activator {
	/** The single instance of class.
	 *
	 * @var $wcol_activate_instance
	 */
	protected static $wcol_activate_instance = null;

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    3.0.0
	 */
	public static function activate() {
		if ( is_null( self::$wcol_activate_instance ) ) {
			self::$wcol_activate_instance = new self();
		}
		return self::$wcol_activate_instance;
	}

	/**
	 * Construct of the activator class.
	 *
	 * @since    3.0.0
	 */
	public function __construct() {
		if ( class_exists( 'WC_Order_limit' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			add_action( 'admin_notices', array( $this, 'xsollwc_admin_notice__error' ) );
		}
		$this->wcol_activation();
	}
	/**
	 * Show Error notice.
	 *
	 * @since    1.0.0
	 */
	public function xsollwc_admin_notice__error() {
		$class   = 'notice notice-error';
		$message = esc_html__( 'Order limit for woocommerce has activated so Order limit free version  is deactivate beacuse  both version not run at same time', 'order-limit-for-woocommerce' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}
	/**
	 * Run function when plugin activate.
	 *
	 * @since    3.0.0
	 */
	public function wcol_activation() {
		$this->create_wcol_defaults();
	}
	/**
	 * Set default setting of plugin when activate.
	 *
	 * @since    3.0.0
	 */
	public function create_wcol_defaults() {
		register_post_type(
			'wcol_rule',
			array(
				'labels'              => array(
					'name'               => esc_html__( 'Rules', 'order-limit-for-woocommerce' ),
					'singular_name'      => esc_html__( 'Rule', 'order-limit-for-woocommerce' ),
					'menu_name'          => _x( 'WC Order Limit', 'Admin menu name', 'order-limit-for-woocommerce' ),
					'all_items'          => __( 'Rules', 'order-limit-for-woocommerce' ),
					'add_new'            => __( 'Add rule', 'order-limit-for-woocommerce' ),
					'add_new_item'       => __( 'Add new rule', 'order-limit-for-woocommerce' ),
					'edit'               => __( 'Edit', 'order-limit-for-woocommerce' ),
					'edit_item'          => __( 'Edit rule', 'order-limit-for-woocommerce' ),
					'new_item'           => __( 'New rule', 'order-limit-for-woocommerce' ),
					'search_items'       => __( 'Search rules', 'order-limit-for-woocommerce' ),
					'not_found'          => __( 'No Rules found', 'order-limit-for-woocommerce' ),
					'not_found_in_trash' => __( 'No Rules found in trash', 'order-limit-for-woocommerce' ),
				),
				'public'              => false,
				'show_ui'             => true,
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_in_menu'        => true,
				'hierarchical'        => false,
				'show_in_nav_menus'   => false,
				'rewrite'             => array( 'slug' => 'wcol_rule' ),
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'menu_position'       => 20,
				'menu_icon'           => 'dashicons-lock',

			)
		);
		$defaults = array(
			'wcol_settings'      => array(
				'enable_store_limit'                       => 'off',
				'store_limit_message'                      => '{store-name} are closed now. It will open tommorrow 9am',
				'store_message_total_amount'               => '{store-name} is sold max orders limit {max-order-limit} or max amount limit  {max-amount-limit} in {time-span}, Sale limit is reached. You will be able to place buy more after {limit-reset-day}. ',
				'product_limit_message'                    => '{product-name} product minimum {applied-on} should be greater than {min-limit} and less than {max-limit}.',
				'shortcode_product_limit_message'          => '{product-name} product minimum {applied-on} should be greater than {min-limit} and less than {max-limit}.',
				'product_limit_message_across_all_orders'  => 'You can buy maximum {max-limit} items of {product-name} {time-span}, your limit is reached. You will be able to place buy more after {limit-reset-day}.',
				'shortcode_product_limit_message_orders'   => 'You can buy maximum {max-limit} items of {product-name} {time-span}, your limit is reached. You will be able to place buy more after {limit-reset-day}.',
				'parent_product_limit_message'             => 'You must add this product {parent-product-names} in cart to proceed',
				'product_limit_message_across_all_users_orders' => '{product-name} is sold max limit {max-limit} .{product-name} is remaining items are {remaining}',
				'product_limit_message_accomulative'       => 'Following products accomulative {applied-on} should be greater than {min-limit} and less than {max-limit}.<br/>{product-names}.',
				'category_limit_message'                   => '{category-name} category item minimum {applied-on} should be greater than {min-limit} and less than {max-limit}.',
				'shortcode_category_limit_message'         => '{category-name} category item minimum {applied-on} should be greater than {min-limit} and less than {max-limit}.',
				'category_limit_message_across_all_orders' => 'You can buy maximum {max-limit} items of {category-name} {time-span}, your limit is reached. You will be able to place buy more after {limit-reset-day}.',
				'shortcode_category_limit_message_orders'  => 'You can buy maximum {max-limit} items of {category-name} {time-span}, your limit is reached. You will be able to place buy more after {limit-reset-day}.',
				'category_limit_message_across_all_users_orders' => '{category-name} is sold max limit are {max-limit} . {category-name} is remaining items are {remaining}.',
				'parent_category_limit_message'            => 'You must add this category product {parent-category-names} in cart to proceed',
				'category_limit_message_accomulative'      => 'Following Categorys products accomulative {applied-on} should be greater than {min-limit} and less than {max-limit}.<br/>{category-names}.',
				'vendor_limit_message'                     => '{vendor-shop-name} item minimum {applied-on} should be greater than {min-limit} and less than {max-limit}.',
				'cart_total_limit_message'                 => 'You must have an order with a minimum of {min-limit} and maximum of {max-limit} {applied-on} to place this order.',
				'cart_single_cat_limit_message'            => 'You must have same or one category products in cart',
				'customer_message'                         => 'You can place {rule-limit} order(s) {time-span}, your limit is reached. You will be able to place your order after {limit-reset-day}.',
				'shortcode_customer_message'               => 'You can place {rule-limit} order(s) {time-span}, your limit is reached. You will be able to place your order after {limit-reset-day}.',
				'customer_message_total_amount'            => 'You can order maximum of {rule-limit} amount {time-span}, your limit is reached. You will be able to place order after {limit-reset-day}.',
				'shortcode_customer_message_total_amount'  => 'You can order maximum of {rule-limit} amount {time-span}, your limit is reached. You will be able to place order after {limit-reset-day}.',
				'enable_product_limit'                     => 'on',
				'enable_category_limit'                    => 'on',
				'enable_cart_total_limit'                  => 'on',
				'enable_checkout_button'                   => 'on',
				'enable_vendor_limit'                      => 'on',
				'enable_customer_limit'                    => 'on',
				'weekly_limit_reset_date'                  => 'monday',
				'monthly_limit_reset_date'                 => '1',
			),
			'version'            => WC_ORDER_LIMIT_VERSION,
			'wcol_exclude_rules' => array(),
		);
		if ( is_multisite() ) {
			$sites = get_sites();
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );
				$wcol_options = get_option( 'wcol_options' );
				if ( ( isset( $wcol_options['version'] ) && $wcol_options['version'] < WC_ORDER_LIMIT_VERSION ) || ( ! isset( $wcol_options['version'] ) && ! empty( $wcol_options ) ) ) {
					if ( isset( $wcol_options['wcol_limit_rules'] ) && ! empty( $wcol_options['wcol_limit_rules'] ) ) {
						if ( is_array( $wcol_options['wcol_limit_rules'] ) ) {
							foreach ( $wcol_options['wcol_limit_rules'] as $key => $rule ) {
								if ( 'product' === $rule['object_type'] ) {
									$object_type = 'products';
								}
								if ( 'product_cat' === $rule['object_type'] ) {
									$object_type = 'categories';
								}
								if ( 'vendor' === $rule['object_type'] ) {
									$object_type = 'vendor';
								}
								$post_title = null;
								$post_title = $rule['object_type'] . ' rule ' . $rule['wcol_min_order_limit'] . ' - ' . $rule['wcol_max_order_limit'];
								$post_arr   = array(
									'post_title'  => $post_title,
									'post_status' => 'publish',
									'post_type'   => 'wcol_rule',
									'meta_input'  => array(
										'wcol-post-rules' => array(
											'rule-type'    => $object_type,
											'obj_ids'      => isset( $rule['object_ids'] ) ? $rule['object_ids'] : array(),
											'min-rule-limit' => isset( $rule['wcol_min_order_limit'] ) ? $rule['wcol_min_order_limit'] : '',
											'applied_on'   => isset( $rule['wcol_applied_on'] ) ? $rule['wcol_applied_on'] : '',
											'editable'     => isset( $rule['editable'] ) ? $rule['editable'] : '',
											'disable-limit' => isset( $rule['disable-limit'] ) ? $rule['disable-limit'] : '',
											'accomulative' => isset( $rule['accomulative'] ) ? $rule['accomulative'] : '',
											'across-all-orders' => isset( $rule['across-all-orders'] ) ? $rule['across-all-orders'] : '',
											'enable-max-rule-limit' => isset( $rule['enable-max-rule-limit'] ) ? $rule['enable-max-rule-limit'] : '',
											'max-rule-limit' => isset( $rule['wcol_max_order_limit'] ) ? $rule['wcol_max_order_limit'] : '',
											'enable-time-limit' => isset( $rule['enable_time_limit'] ) ? $rule['enable_time_limit'] : '',
											'rule-time-span' => isset( $rule['wcol_rule_time_span'] ) ? $rule['wcol_rule_time_span'] : '',
											'yearly-start-day' => isset( $rule['wcol_yearly_start_day'] ) ? $rule['wcol_yearly_start_day'] : '',
											'yearly-start-month' => isset( $rule['wcol_yearly_start_month'] ) ? $rule['wcol_yearly_start_month'] : '',
											'weekly-start-day' => isset( $rule['wcol_weekly_start_day'] ) ? $rule['wcol_weekly_start_day'] : '',
											'monthly-start-date' => isset( $rule['wcol_monthly_start_date'] ) ? $rule['wcol_monthly_start_date'] : '',
											'rule-start-time' => isset( $rule['wcol_rule_start_time'] ) ? $rule['wcol_rule_start_time'] : '',
											'rule-end-time' => isset( $rule['wcol_rule_end_time'] ) ? $rule['wcol_rule_end_time'] : '',
											'enable-for-users' => isset( $rule['enable_for_users'] ) ? $rule['enable_for_users'] : '',
											'user-type'    => isset( $rule['wcol_rule_user_type'] ) ? $rule['wcol_rule_user_type'] : '',
											'rule-roles'   => isset( $rule['wcol_rule_roles'] ) ? $rule['wcol_rule_roles'] : '',
											'rule-users'   => isset( $rule['wcol_rule_users'] ) ? $rule['wcol_rule_users'] : '',
										),
										'wcol-rule-type'  => $object_type,
									),
								);
								$id         = wp_insert_post( $post_arr );
							}
						}
					}
					if ( isset( $wcol_options['wcol_customer_rules'] ) && ! empty( $wcol_options['wcol_customer_rules'] ) ) {
						if ( is_array( $wcol_options['wcol_customer_rules'] ) ) {
							foreach ( $wcol_options['wcol_customer_rules'] as $key => $rule ) {
								$post_title = null;
								$post_title = $rule['object_type'] . ' rule ' . $rule['wcol_rule_limit'];
								$post_arr   = array(
									'post_title'     => $post_title,
									'post_status'    => 'publish',
									'post_type'      => 'wcol_rule',
									'meta_input'     => array(
										'wcol-post-rules' => array(
											'rule-type' => 'customer',
											'obj_ids'   => isset( $rule['object_type'] ) ? array( $rule['object_type'] ) : array(),
											'cus_applied_on' => isset( $rule['wcol_applied_on'] ) ? $rule['wcol_applied_on'] : '',
											'disable-limit' => isset( $rule['disable-limit'] ) ? $rule['disable-limit'] : '',
											'cus-max-rule-limit' => isset( $rule['wcol_rule_limit'] ) ? $rule['wcol_rule_limit'] : '',
											'enable-time-limit' => isset( $rule['enable_time_limit'] ) ? $rule['enable_time_limit'] : '',
											'rule-time-span' => isset( $rule['wcol_rule_time_span'] ) ? $rule['wcol_rule_time_span'] : '',
											'yearly-start-day' => isset( $rule['wcol_yearly_start_day'] ) ? $rule['wcol_yearly_start_day'] : '',
											'yearly-start-month' => isset( $rule['wcol_yearly_start_month'] ) ? $rule['wcol_yearly_start_month'] : '',
											'weekly-start-day' => isset( $rule['wcol_weekly_start_day'] ) ? $rule['wcol_weekly_start_day'] : '',
											'monthly-start-date' => isset( $rule['wcol_monthly_start_date'] ) ? $rule['wcol_monthly_start_date'] : '',
											'rule-start-time' => isset( $rule['wcol_rule_start_time'] ) ? $rule['wcol_rule_start_time'] : '',
											'rule-end-time' => isset( $rule['wcol_rule_end_time'] ) ? $rule['wcol_rule_end_time'] : '',
											'cus-rule-users' => isset( $rule['users'] ) ? $rule['users'] : '',
											'cus-rule-roles' => isset( $rule['roles'] ) ? $rule['roles'] : '',
										),
									),
									'wcol-rule-type' => $object_type,
								);
								$id         = wp_insert_post( $post_arr );
							}
						}
					}
					$new_wcol_options                       = array();
					$new_wcol_options['wcol_settings']      = $wcol_options['wcol_settings'];
					$new_wcol_options['version']            = WC_ORDER_LIMIT_VERSION;
					$new_wcol_options['wcol_exclude_rules'] = $wcol_options['wcol_exclude_rules'];
					update_option( 'wcol_options', $new_wcol_options );
				}
				if ( empty( $wcol_options ) || ! is_array( $wcol_options ) ) {
					add_option( 'wcol_options', $defaults );
				}
				restore_current_blog();
			}
		} else {
			$wcol_options = get_option( 'wcol_options' );
			if ( ( isset( $wcol_options['version'] ) && $wcol_options['version'] < WC_ORDER_LIMIT_VERSION ) || ( ! isset( $wcol_options['version'] ) && ! empty( $wcol_options ) ) ) {
				if ( isset( $wcol_options['wcol_limit_rules'] ) && ! empty( $wcol_options['wcol_limit_rules'] ) ) {
					if ( is_array( $wcol_options['wcol_limit_rules'] ) ) {
						foreach ( $wcol_options['wcol_limit_rules'] as $key => $rule ) {
							if ( 'product' === $rule['object_type'] ) {
								$object_type = 'products';
							}
							if ( 'product_cat' === $rule['object_type'] ) {
								$object_type = 'categories';
							}
							if ( 'vendor' === $rule['object_type'] ) {
								$object_type = 'vendor';
							}
							$post_title = null;
							$post_title = $rule['object_type'] . ' rule ' . $rule['wcol_min_order_limit'] . ' - ' . $rule['wcol_max_order_limit'];
							$post_arr   = array(
								'post_title'  => $post_title,
								'post_status' => 'publish',
								'post_type'   => 'wcol_rule',
								'meta_input'  => array(
									'wcol-post-rules' => array(
										'rule-type'        => $object_type,
										'obj_ids'          => isset( $rule['object_ids'] ) ? $rule['object_ids'] : array(),
										'min-rule-limit'   => isset( $rule['wcol_min_order_limit'] ) ? $rule['wcol_min_order_limit'] : '',
										'applied_on'       => isset( $rule['wcol_applied_on'] ) ? $rule['wcol_applied_on'] : '',
										'editable'         => isset( $rule['editable'] ) ? $rule['editable'] : '',
										'disable-limit'    => isset( $rule['disable-limit'] ) ? $rule['disable-limit'] : '',
										'accomulative'     => isset( $rule['accomulative'] ) ? $rule['accomulative'] : '',
										'across-all-orders' => isset( $rule['across-all-orders'] ) ? $rule['across-all-orders'] : '',
										'enable-max-rule-limit' => isset( $rule['enable-max-rule-limit'] ) ? $rule['enable-max-rule-limit'] : '',
										'max-rule-limit'   => isset( $rule['wcol_max_order_limit'] ) ? $rule['wcol_max_order_limit'] : '',
										'enable-time-limit' => isset( $rule['enable_time_limit'] ) ? $rule['enable_time_limit'] : '',
										'rule-time-span'   => isset( $rule['wcol_rule_time_span'] ) ? $rule['wcol_rule_time_span'] : '',
										'yearly-start-day' => isset( $rule['wcol_yearly_start_day'] ) ? $rule['wcol_yearly_start_day'] : '',
										'yearly-start-month' => isset( $rule['wcol_yearly_start_month'] ) ? $rule['wcol_yearly_start_month'] : '',
										'weekly-start-day' => isset( $rule['wcol_weekly_start_day'] ) ? $rule['wcol_weekly_start_day'] : '',
										'monthly-start-date' => isset( $rule['wcol_monthly_start_date'] ) ? $rule['wcol_monthly_start_date'] : '',
										'rule-start-time'  => isset( $rule['wcol_rule_start_time'] ) ? $rule['wcol_rule_start_time'] : '',
										'rule-end-time'    => isset( $rule['wcol_rule_end_time'] ) ? $rule['wcol_rule_end_time'] : '',
										'enable-for-users' => isset( $rule['enable_for_users'] ) ? $rule['enable_for_users'] : '',
										'user-type'        => isset( $rule['wcol_rule_user_type'] ) ? $rule['wcol_rule_user_type'] : '',
										'rule-roles'       => isset( $rule['wcol_rule_roles'] ) ? $rule['wcol_rule_roles'] : '',
										'rule-users'       => isset( $rule['wcol_rule_users'] ) ? $rule['wcol_rule_users'] : '',
									),
									'wcol-rule-type'  => $object_type,
								),
							);
							$id         = wp_insert_post( $post_arr );
						}
					}
				}
				if ( isset( $wcol_options['wcol_customer_rules'] ) && ! empty( $wcol_options['wcol_customer_rules'] ) ) {
					if ( is_array( $wcol_options['wcol_customer_rules'] ) ) {
						foreach ( $wcol_options['wcol_customer_rules'] as $key => $rule ) {
							$post_title = null;
							$post_title = $rule['object_type'] . ' rule ' . $rule['wcol_rule_limit'];
							$post_arr   = array(
								'post_title'     => $post_title,
								'post_status'    => 'publish',
								'post_type'      => 'wcol_rule',
								'meta_input'     => array(
									'wcol-post-rules' => array(
										'rule-type'        => 'customer',
										'obj_ids'          => isset( $rule['object_type'] ) ? array( $rule['object_type'] ) : array(),
										'cus_applied_on'   => isset( $rule['wcol_applied_on'] ) ? $rule['wcol_applied_on'] : '',
										'disable-limit'    => isset( $rule['disable-limit'] ) ? $rule['disable-limit'] : '',
										'cus-max-rule-limit' => isset( $rule['wcol_rule_limit'] ) ? $rule['wcol_rule_limit'] : '',
										'enable-time-limit' => isset( $rule['enable_time_limit'] ) ? $rule['enable_time_limit'] : '',
										'rule-time-span'   => isset( $rule['wcol_rule_time_span'] ) ? $rule['wcol_rule_time_span'] : '',
										'yearly-start-day' => isset( $rule['wcol_yearly_start_day'] ) ? $rule['wcol_yearly_start_day'] : '',
										'yearly-start-month' => isset( $rule['wcol_yearly_start_month'] ) ? $rule['wcol_yearly_start_month'] : '',
										'weekly-start-day' => isset( $rule['wcol_weekly_start_day'] ) ? $rule['wcol_weekly_start_day'] : '',
										'monthly-start-date' => isset( $rule['wcol_monthly_start_date'] ) ? $rule['wcol_monthly_start_date'] : '',
										'rule-start-time'  => isset( $rule['wcol_rule_start_time'] ) ? $rule['wcol_rule_start_time'] : '',
										'rule-end-time'    => isset( $rule['wcol_rule_end_time'] ) ? $rule['wcol_rule_end_time'] : '',
										'cus-rule-users'   => isset( $rule['users'] ) ? $rule['users'] : '',
										'cus-rule-roles'   => isset( $rule['roles'] ) ? $rule['roles'] : '',
									),
								),
								'wcol-rule-type' => $object_type,
							);
							$id         = wp_insert_post( $post_arr );
						}
					}
				}
				$new_wcol_options                       = array();
				$new_wcol_options['wcol_settings']      = $wcol_options['wcol_settings'];
				$new_wcol_options['version']            = WC_ORDER_LIMIT_VERSION;
				$new_wcol_options['wcol_exclude_rules'] = $wcol_options['wcol_exclude_rules'];
				update_option( 'wcol_options', $new_wcol_options );
			}
			if ( empty( $wcol_options ) || ! is_array( $wcol_options ) ) {
				add_option( 'wcol_options', $defaults );
			}
		}
	}
}
