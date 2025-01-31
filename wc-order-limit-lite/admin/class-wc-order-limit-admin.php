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

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/admin
 * @author     xfinitysoft <support@xfinitysoft.com>
 */
class WC_Order_Limit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}
	/**
	 * Initialize the plugin.
	 *
	 * @since 3.0.0
	 */
	public function wcol_init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', 'wc_order_limit_missing_wc_notice' );
			return;
		}
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    3.0.0
	 */
	public function enqueue_styles() {
		global $post;
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WC_Order_Limit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WC_Order_Limit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		// Registering Admin Styles.
		wp_register_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version );
		//phpcs:ignore
		if ( ( isset( $_GET['page'] ) && 'wcol_settings' === $_GET['page'] ) || ( isset( $_GET['page'] ) && 'wcol-support' === $_GET['page'] ) ) {
			wp_enqueue_style( 'bootstrap-css' );
		}
		wp_enqueue_style( 'select2-css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version );
		wp_enqueue_style( 'jquery-rain-date-time-css', plugin_dir_url( __FILE__ ) . 'css/jquery-rain-date-time.min.css', array(), $this->version );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-order-limit-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    3.0.0
	 */
	public function enqueue_scripts() {
		global $post;
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WC_Order_Limit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WC_Order_Limit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script( 'tether-js', 'https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js', array(), time(), false );
		wp_register_script( 'bootstraps-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array(), time(), false );
		// phpcs:ignore
		if ( ( isset( $_GET['page'] ) && 'wcol_settings' === $_GET['page'] ) || ( isset( $_GET['page'] ) && 'wcol-support' === $_GET['page'] ) ) {
			wp_enqueue_script( 'tether-js' );
			wp_enqueue_script( 'bootstraps-js' );
		}

		wp_enqueue_script( 'select2-js', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array(), time(), false );
		wp_enqueue_script( 'jquery-rain-date-time-js', plugin_dir_url( __FILE__ ) . 'js/jquery-rain-date-time.js', array(), time(), false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-order-limit-admin.js', array( 'jquery' ), $this->version, false );
		$script_vars = $this->get_wcol_script_vars();
		wp_localize_script( $this->plugin_name, 'wcol_script_vars', $script_vars );
		if ( is_object( $post ) && 'wcol_rule' === $post->post_type ) {
			wp_enqueue_script( $this->plugin_name . '-rule-type', plugin_dir_url( __FILE__ ) . 'js/wc-order-limit-rule.js', array( 'jquery' ), $this->version, true );
		}
	}

	/**
	 * Initialization setting class.
	 *
	 * @since    3.0.0
	 *
	 * @param array $settings_pages array of settings pages.
	 */
	public function wcol_woocommerce_settings_page( $settings_pages ) {
		$settings_pages[] = include __DIR__ . '/class-wc-order-limit-settings.php';
		return $settings_pages;
	}
	/**
	 * Create custom posttype.
	 *
	 * @since    3.0.0
	 */
	public function wcol_create_post_type() {
		register_post_type(
			'wcol_rule',
			array(
				'labels'              => array(
					'name'               => esc_html__( 'Rules', 'order-limit-for-woocommerce' ),
					'singular_name'      => esc_html__( 'Rule', 'order-limit-for-woocommerce' ),
					'menu_name'          => _x( 'WC Order Limit', 'Admin menu name', 'order-limit-for-woocommerce' ),
					'all_items'          => __( 'Rules', 'order-limit-for-woocommerce' ),
					'add_new'            => __( 'Add Rule', 'order-limit-for-woocommerce' ),
					'add_new_item'       => __( 'Add New Rule', 'order-limit-for-woocommerce' ),
					'edit'               => __( 'Edit', 'order-limit-for-woocommerce' ),
					'edit_item'          => __( 'Edit Rule', 'order-limit-for-woocommerce' ),
					'new_item'           => __( 'New Rule', 'order-limit-for-woocommerce' ),
					'search_items'       => __( 'Search Rules', 'order-limit-for-woocommerce' ),
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
				'show_in_rest'        => true,

			)
		);
		$defaults     = array(
			'wcol_settings'      => array(
				'enable_store_limit'                       => 'on',
				'store_limit_message'                      => '{store-name} are closed now. It will open tommorrow 9am',
				'store_message_total_amount'               => '{store-name} is sold max orders limit {max-order-limit} or max amount limit  {max-amount-limit} in {time-span}, Sale limit is reached. You will be able to place buy more after {limit-reset-day}. ',
				'product_limit_message'                    => '{product-name} product minimum {applied-on} should be greater than {min-limit} and less than {max-limit}.',
				'product_limit_message_across_all_orders'  => 'You can buy maximum {max-limit} items of {product-name} {time-span}, your limit is reached. You will be able to place buy more after {limit-reset-day}.',
				'product_limit_message_across_all_users_orders' => '{product-name} is sold max limit {max-limit} .{product-name} is remaining items are {remaining}',
				'product_limit_message_accomulative'       => 'Following products accomulative {applied-on} should be greater than {min-limit} and less than {max-limit}.<br/>{product-names}.',
				'category_limit_message'                   => '{category-name} category item minimum {applied-on} should be greater than {min-limit} and less than {max-limit}.',
				'category_limit_message_across_all_orders' => 'You can buy maximum {max-limit} items of {category-name} {time-span}, your limit is reached. You will be able to place buy more after {limit-reset-day}.',
				'category_limit_message_across_all_users_orders' => '{category-name} is sold max limit are {max-limit} . {category-name} is remaining items are {remaining}.',
				'category_limit_message_accomulative'      => 'Following Categorys products accomulative {applied-on} should be greater than {min-limit} and less than {max-limit}.<br/>{category-names}.',
				'vendor_limit_message'                     => '{vendor-shop-name} item minimum {applied-on} should be greater than {min-limit} and less than {max-limit}.',
				'cart_total_limit_message'                 => 'You must have an order with a minimum of {min-limit} and maximum of {max-limit} {applied-on} to place this order.',
				'customer_message'                         => 'You can place {rule-limit} order(s) {time-span}, your limit is reached. You will be able to place your order after {limit-reset-day}.',
				'customer_message_total_amount'            => 'You can order maximum of {rule-limit} amount {time-span}, your limit is reached. You will be able to place order after {limit-reset-day}.',
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
						$post_arr = array(
							'post_title'  => $rule['object_type'] . ' rule ' . $rule['wcol_min_order_limit'] . ' - ' . $rule['wcol_max_order_limit'],
							'post_status' => 'publish',
							'post_type'   => 'wcol_rule',
							'meta_input'  => array(
								'wcol-post-rules' => array(
									'rule-type'          => $object_type,
									'obj_ids'            => isset( $rule['object_ids'] ) ? $rule['object_ids'] : array(),
									'min-rule-limit'     => isset( $rule['wcol_min_order_limit'] ) ? $rule['wcol_min_order_limit'] : '',
									'applied_on'         => isset( $rule['wcol_applied_on'] ) ? $rule['wcol_applied_on'] : '',
									'editable'           => isset( $rule['editable'] ) ? $rule['editable'] : '',
									'disable-limit'      => isset( $rule['disable-limit'] ) ? $rule['disable-limit'] : '',
									'accomulative'       => isset( $rule['accomulative'] ) ? $rule['accomulative'] : '',
									'across-all-orders'  => isset( $rule['across-all-orders'] ) ? $rule['across-all-orders'] : '',
									'enable-max-rule-limit' => isset( $rule['enable-max-rule-limit'] ) ? $rule['enable-max-rule-limit'] : '',
									'max-rule-limit'     => isset( $rule['wcol_max_order_limit'] ) ? $rule['wcol_max_order_limit'] : '',
									'enable-time-limit'  => isset( $rule['enable_time_limit'] ) ? $rule['enable_time_limit'] : '',
									'rule-time-span'     => isset( $rule['wcol_rule_time_span'] ) ? $rule['wcol_rule_time_span'] : '',
									'yearly-start-day'   => isset( $rule['wcol_yearly_start_day'] ) ? $rule['wcol_yearly_start_day'] : '',
									'yearly-start-month' => isset( $rule['wcol_yearly_start_month'] ) ? $rule['wcol_yearly_start_month'] : '',
									'weekly-start-day'   => isset( $rule['wcol_weekly_start_day'] ) ? $rule['wcol_weekly_start_day'] : '',
									'monthly-start-date' => isset( $rule['wcol_monthly_start_date'] ) ? $rule['wcol_monthly_start_date'] : '',
									'rule-start-time'    => isset( $rule['wcol_rule_start_time'] ) ? $rule['wcol_rule_start_time'] : '',
									'rule-end-time'      => isset( $rule['wcol_rule_end_time'] ) ? $rule['wcol_rule_end_time'] : '',
									'enable-for-users'   => isset( $rule['enable_for_users'] ) ? $rule['enable_for_users'] : '',
									'user-type'          => isset( $rule['wcol_rule_user_type'] ) ? $rule['wcol_rule_user_type'] : '',
									'rule-roles'         => isset( $rule['wcol_rule_roles'] ) ? $rule['wcol_rule_roles'] : '',
									'rule-users'         => isset( $rule['wcol_rule_users'] ) ? $rule['wcol_rule_users'] : '',
								),
								'wcol-rule-type'  => $object_type,
							),
						);
						$id       = wp_insert_post( $post_arr );
					}
				}
			}
			if ( isset( $wcol_options['wcol_customer_rules'] ) && ! empty( $wcol_options['wcol_customer_rules'] ) ) {
				if ( is_array( $wcol_options['wcol_customer_rules'] ) ) {
					foreach ( $wcol_options['wcol_customer_rules'] as $key => $rule ) {
						$post_arr = array(
							'post_title'     => $rule['object_type'] . ' rule ' . $rule['wcol_rule_limit'],
							'post_status'    => 'publish',
							'post_type'      => 'wcol_rule',
							'meta_input'     => array(
								'wcol-post-rules' => array(
									'rule-type'          => 'customer',
									'obj_ids'            => isset( $rule['object_type'] ) ? array( $rule['object_type'] ) : array(),
									'cus_applied_on'     => isset( $rule['wcol_applied_on'] ) ? $rule['wcol_applied_on'] : '',
									'disable-limit'      => isset( $rule['disable-limit'] ) ? $rule['disable-limit'] : '',
									'cus-max-rule-limit' => isset( $rule['wcol_rule_limit'] ) ? $rule['wcol_rule_limit'] : '',
									'enable-time-limit'  => isset( $rule['enable_time_limit'] ) ? $rule['enable_time_limit'] : '',
									'rule-time-span'     => isset( $rule['wcol_rule_time_span'] ) ? $rule['wcol_rule_time_span'] : '',
									'yearly-start-day'   => isset( $rule['wcol_yearly_start_day'] ) ? $rule['wcol_yearly_start_day'] : '',
									'yearly-start-month' => isset( $rule['wcol_yearly_start_month'] ) ? $rule['wcol_yearly_start_month'] : '',
									'weekly-start-day'   => isset( $rule['wcol_weekly_start_day'] ) ? $rule['wcol_weekly_start_day'] : '',
									'monthly-start-date' => isset( $rule['wcol_monthly_start_date'] ) ? $rule['wcol_monthly_start_date'] : '',
									'rule-start-time'    => isset( $rule['wcol_rule_start_time'] ) ? $rule['wcol_rule_start_time'] : '',
									'rule-end-time'      => isset( $rule['wcol_rule_end_time'] ) ? $rule['wcol_rule_end_time'] : '',
									'cus-rule-users'     => isset( $rule['users'] ) ? $rule['users'] : '',
									'cus-rule-roles'     => isset( $rule['roles'] ) ? $rule['roles'] : '',
								),
							),
							'wcol-rule-type' => $object_type,
						);
						$id       = wp_insert_post( $post_arr );
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
	/**
	 * Register metabox.
	 *
	 * @since    3.0.0
	 */
	public function wcol_register_metabox() {
		add_meta_box( 'wcol_rule_limits', esc_html__( 'Rule type', 'order-limit-for-woocommerce' ), array( $this, 'wcol_rule_limits_callback' ), 'wcol_rule' );
	}
	/**
	 * Callback function meta box.
	 *
	 * @since    3.0.0
	 * @param object $post Detail of rule post.
	 */
	public function wcol_rule_limits_callback( $post ) {
		$months_array = array(
			'01' => esc_html__( 'Jan', 'order-limit-for-woocommerce' ),
			'02' => esc_html__( 'Feb', 'order-limit-for-woocommerce' ),
			'03' => esc_html__( 'Mar', 'order-limit-for-woocommerce' ),
			'04' => esc_html__( 'Apr', 'order-limit-for-woocommerce' ),
			'05' => esc_html__( 'May', 'order-limit-for-woocommerce' ),
			'06' => esc_html__( 'Jun', 'order-limit-for-woocommerce' ),
			'07' => esc_html__( 'Jul', 'order-limit-for-woocommerce' ),
			'08' => esc_html__( 'Aug', 'order-limit-for-woocommerce' ),
			'09' => esc_html__( 'Sep', 'order-limit-for-woocommerce' ),
			'10' => esc_html__( 'Oct', 'order-limit-for-woocommerce' ),
			'11' => esc_html__( 'Nov', 'order-limit-for-woocommerce' ),
			'12' => esc_html__( 'Dec', 'order-limit-for-woocommerce' ),
		);
		$weeks_array  = array(
			'monday'    => esc_html__( 'Monday', 'order-limit-for-woocommerce' ),
			'tuesday'   => esc_html__( 'Tuesday', 'order-limit-for-woocommerce' ),
			'wednesday' => esc_html__( 'Wednesday', 'order-limit-for-woocommerce' ),
			'thursday'  => esc_html__( 'Thursday', 'order-limit-for-woocommerce' ),
			'friday'    => esc_html__( 'Friday', 'order-limit-for-woocommerce' ),
			'saturday'  => esc_html__( 'Saturday', 'order-limit-for-woocommerce' ),
			'sunday'    => esc_html__( 'Sunday', 'order-limit-for-woocommerce' ),
		);
		$rule         = get_post_meta( $post->ID, 'wcol-post-rules', true );
		include plugin_dir_path( __FILE__ ) . 'views/wc-order-limit-rule-metabox-views.php';
	}
	/**
	 * Save metabox data function.
	 *
	 * @since    3.0.0
	 * @param object $post_id Id of rule post.
	 */
	public function wcol_save_metabox( $post_id ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_wcol_save_rules_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wcol_save_rules_nonce'] ) ), 'wcol_save_rules' ) ) {
			return;
		}
		if ( isset( $_POST['wcol-rules'] ) && 'wcol_rule' === get_post_type( $post_id ) ) {
			//phpcs:ignore
			$rules = $this->wcol_sanitize( $_POST['wcol-rules'] );
			if ( 'none' === $rules['rule-type'] ) {
				update_option( 'wcol_rule_type_error', 1 );
				return false;
			}
			if ( empty( $rules['obj_ids'] ) ) {
				update_option( 'wcol_rule_select_items', 1 );
				return false;
			}
			if ( empty( $rules['min-rule-limit'] ) ) {
				$rules['min-rule-limit'] = 0;
			}
			update_post_meta( $post_id, 'wcol-post-rules', $rules );
			update_post_meta( $post_id, 'wcol-rule-type', $rules['rule-type'] );
		}
	}
	/**
	 * Add plugin notice.
	 *
	 * @since    3.0.0
	 */
	public function wcol_add_plugin_notice() {
		if ( 1 === get_option( 'wcol_rule_type_error' ) ) {
			// check whether to display the message.
			add_action( 'admin_notices', array( $this, 'rule_type_show_error' ) );
			// turn off the message.
			update_option( 'wcol_rule_type_error', 0 );
		}
		if ( 1 === get_option( 'wcol_rule_select_items' ) ) {
			// check whether to display the message.
			add_action( 'admin_notices', array( $this, 'select_item_show_error' ) );
			// turn off the message.
			update_option( 'wcol_rule_select_items', 0 );
		}
	}
	/**
	 * Callback function plugin Error show.
	 *
	 * @since    3.0.0
	 */
	public function rule_type_show_error() {
		echo '<div class="error">
       <p>Please Select Rule Type</p>
       </div>';
	}

	/**
	 * Callback function plugin Error show.
	 *
	 * @since    3.0.0
	 */
	public function select_item_show_error() {
		echo '<div class="error">
       <p>Please Select items</p>
       </div>';
	}
	/**
	 * Sanitize function rules.
	 *
	 * @since    3.0.0
	 * @param array $rules Array of rules detail.
	 */
	public function wcol_sanitize( $rules ) {
		foreach ( $rules as $key => $value ) {
			if ( is_array( $value ) ) {
				$rules[ $key ] = array_map( 'sanitize_text_field', $value );
			} else {
				$rules[ $key ] = sanitize_text_field( $value );
			}
		}
		return $rules;
	}

	/**
	 * Sanitize function rules.
	 *
	 * @since    3.0.0
	 */
	public function get_wcol_script_vars() {
		$script_var = array(
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'customer_type' => $this->get_customer_types(),
			'user_roles'    => $this->get_all_roles(),
			'memberships'   => $this->get_all_memberships(),
		);
		return $script_var;
	}


	/**
	 * Get the product.
	 *
	 * @since    3.0.0
	 */
	public function wcol_get_product() {
		global $wpdb;
		$product_type = 'simple';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['type'] ) && ! empty( $_GET['type'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$product_type = sanitize_text_field( wp_unslash( $_GET['type'] ) );
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$serach = isset( $_GET['q'] ) ? '%' . sanitize_text_field( wp_unslash( $_GET['q'] ) ) . '%' : '';
		$limit  = 10;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 1;
		$offset = $limit * ( $page - 1 );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$total       = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_status IN ('pending', 'publish', 'private') AND post_type='product' AND post_name LIKE %s", $serach ) );
		$total_count = count( $total );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$products = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT posts.ID as id, posts.post_title as text From {$wpdb->prefix}posts AS posts
		INNER JOIN {$wpdb->prefix}term_relationships AS term_relationships ON id = term_relationships.object_id
		INNER JOIN {$wpdb->prefix}term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
		INNER JOIN {$wpdb->prefix}terms AS terms ON term_taxonomy.term_id = terms.term_id
		WHERE term_taxonomy.taxonomy = 'product_type'
		AND terms.slug = %s AND posts.post_type = 'product'
		AND posts.post_status IN ('pending', 'publish', 'private')
		AND posts.post_name LIKE %s ORDER BY posts.post_title LIMIT %d OFFSET %d",
				array( $product_type, $serach, $limit, $offset )
			)
		);
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['page'] ) ) {
			$products = array_merge(
				array(
					array(
						'id'   => '-1',
						'text' => 'All Products ',
					),
				),
				$products
			);
		}
		$data = array(
			'items'       => $products,
			'total_count' => $total_count,
		);
		wp_send_json( $data );
		wp_die();
	}

	/**
	 * Get all categories.
	 *
	 * @since    3.0.0
	 */
	public function wcol_get_categories() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$serach = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
		$limit  = 10;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page        = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 1;
		$offset      = $limit * ( $page - 1 );
		$args        = array(
			'taxonomy'     => 'product_cat',
			'fields'       => 'ids',
			'search'       => $serach,
			'orderby'      => 'name',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 0,
			'hide_empty'   => 0,
		);
		$count       = get_terms( $args );
		$total_count = count( $count );
		$args        = array(
			'taxonomy'     => 'product_cat',
			'number'       => $limit,
			'offset'       => $offset,
			'fields'       => 'all',
			'search'       => $serach,
			'orderby'      => 'name',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 0,
			'hide_empty'   => 0,
		);
		$terms       = get_terms( $args );
		$product_cat = array();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['page'] ) ) {
			$product_cat[] = array(
				'id'   => '-1',
				'text' => 'All Categories ',
			);
		}
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$product_cat[] = array(
					'id'   => $term->term_id,
					'text' => $term->name,
				);
			}
		}
		$data = array(
			'items'       => $product_cat,
			'total_count' => $total_count,
		);
		wp_send_json( $data );
		wp_die();
	}
	/**
	 * Get all customer type rules.
	 *
	 * @since    3.0.0
	 */
	public function get_customer_types() {
		$customer_types = array(
			array(
				'id'   => 'all-users',
				'text' => __( 'All Users', 'order-limit-for-woocommerce' ),
			),
			array(
				'id'   => 'every-user',
				'text' => __( 'Every Single User', 'order-limit-for-woocommerce' ),
			),
			array(
				'id'   => 'selective-users',
				'text' => __( 'Selective Users', 'order-limit-for-woocommerce' ),
			),
			array(
				'id'   => 'roles',
				'text' => __( 'Roles', 'order-limit-for-woocommerce' ),
			),
			array(
				'id'   => 'guest-users',
				'text' => __( 'Guest Users', 'order-limit-for-woocommerce' ),
			),
		);
		return $customer_types;
	}

	/**
	 * Get all users.
	 *
	 * @since    3.0.0
	 */
	public function wcol_get_users() {
		global $wpdb;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$serach = isset( $_GET['q'] ) ? '%' . sanitize_text_field( wp_unslash( $_GET['q'] ) ) . '%' : '';
		$limit  = 10;
		if ( is_multisite() ) {
			$wpdb->prefix = $wpdb->get_blog_prefix( 1 );
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 1;
		$offset = $limit * ( $page - 1 );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$total       = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}users WHERE display_name LIKE  %s", $serach ) );
		$total_count = count( $total );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$users = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID as id, display_name as text FROM {$wpdb->prefix}users
				WHERE display_name LIKE %s ORDER BY display_name LIMIT %d  OFFSET %d",
				array( $serach, $limit, $offset )
			)
		);
		$data  = array(
			'items'       => $users,
			'total_count' => $total_count,
		);
		wp_send_json( $data );
		wp_die();
	}

	/**
	 * Get all user roles.
	 *
	 * @since    3.0.0
	 */
	public function get_all_roles() {
		$roles = array();
		global $wp_roles;
		if ( is_array( $wp_roles->roles ) ) {
			foreach ( $wp_roles->roles as $key => $role ) {
				$roles[] = array(
					'id'   => $key,
					'text' => $role['name'],
				);
			}
		}
		return $roles;
	}
	/**
	 * Get all membership setting.
	 *
	 * @since    3.0.0
	 */
	public function get_all_memberships() {
		$memberships = array();
		if ( is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
			global $wpdb;
			$membership_query = "SELECT ID as id, post_title as text FROM {$wpdb->prefix}posts
                            WHERE post_status='publish' AND post_type='wc_membership_plan'
                            ORDER BY post_title";
			//phpcs:ignore
			$memberships = $wpdb->get_results( $membership_query, ARRAY_A );
		}
		return $memberships;
	}
	/**
	 * Callback function setting.
	 *
	 * @since    3.0.0
	 */
	public function wcol_setting_callback() {
		$wcol_options  = get_option( 'wcol_options' );
		$wcol_settings = $wcol_options['wcol_settings'];
		$weeks_array   = array(
			'monday'    => esc_html__( 'Monday', 'order-limit-for-woocommerce' ),
			'tuesday'   => esc_html__( 'Tuesday', 'order-limit-for-woocommerce' ),
			'wednesday' => esc_html__( 'Wednesday', 'order-limit-for-woocommerce' ),
			'thursday'  => esc_html__( 'Thursday', 'order-limit-for-woocommerce' ),
			'friday'    => esc_html__( 'Friday', 'order-limit-for-woocommerce' ),
			'saturday'  => esc_html__( 'Saturday', 'order-limit-for-woocommerce' ),
			'sunday'    => esc_html__( 'Sunday', 'order-limit-for-woocommerce' ),
		);
		$months_array  = array(
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
		?>
		<div class="wrap wcol">
			<h3><?php esc_html_e( 'Exclude Rules', 'order-limit-for-woocommerce' ); ?></h3>
			<div class="xs-wcol-alert xs-wcol-alert-success wcol-data-save-notice">
				<button type="button" class="xs-wcol-close xs-wcol-notice-dismiss" >&times;</button>
				<span></span>
				<?php wp_nonce_field( 'wcol_save_rules', '_wcol_save_rules_nonce', true ); ?>
			</div>
			<div class="wcol-inner">
				<?php
				$wcol_exclude_rules = $wcol_options['wcol_exclude_rules'];
				include plugin_dir_path( __FILE__ ) . 'views/wc-order-limit-exclude.php';
				?>
			</div>
			<!-- </form> -->
			<!-- Modal -->
			<div class="modal fade xs-wcol-modal" id="wcol-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php esc_html_e( 'Press Save button to save the Data', 'order-limit-for-woocommerce' ); ?></h5>
							<button type="button" class="close wcol-rule-button-disable" id='wcol-modal-close' data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<h6><?php esc_html_e( 'Are you sure you want to leave without saving the Data?', 'order-limit-for-woocommerce' ); ?></h6>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary wcol-modal-disable" id='wcol-modal-pwos' data-dismiss="modal"><?php esc_html_e( 'No', 'order-limit-for-woocommerce' ); ?></button>
							<button type="button" class="btn btn-success wcol-modal-disable" id='wcol-modal-sbp'><?php esc_html_e( 'Yes', 'order-limit-for-woocommerce' ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Save setting of rule.
	 *
	 * @since    3.0.0
	 */
	public function wc_order_limit_save_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_wcol_save_rules_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wcol_save_rules_nonce'] ) ), 'wcol_save_rules' ) ) {
			return;
		}
		if ( isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ) {
			$data       = array();
			$old_option = get_option( 'wcol_options', true );
			$exludes    = isset( $old_option['wcol_exclude_rules'] ) ? $old_option['wcol_exclude_rules'] : array();
			$settings   = isset( $old_option['wcol_settings'] ) ? $old_option['wcol_settings'] : array();
			//phpcs:ignore
			parse_str( $_POST['data'], $data );
			$raw_exclude_rules = ( isset( $data['wcol-rules']['exclude-rules'] ) && ! empty( $data['wcol-rules']['exclude-rules'] ) ) ? $data['wcol-rules']['exclude-rules'] : array( 'object-ids' => '' );
			unset( $data['_wp_http_referer'] );
			$wcol_exclude_rules = array();
			if ( '' !== $raw_exclude_rules['object-ids'] && is_array( $raw_exclude_rules['object-ids'] ) ) {
				foreach ( $raw_exclude_rules['object-ids'] as $key => $value ) {
					if ( '' !== $value ) {
						$wcol_exclude_rules[] = array(
							'rule-id'              => stripcslashes( $raw_exclude_rules['rule-id'][ $key ] ),
							'disable-limit'        => stripcslashes( $raw_exclude_rules['disable-limit'][ $key ] ),
							'object_ids'           => $value,
							'object_type'          => 'exclude_product',
							'exclude-limit'        => stripcslashes( $raw_exclude_rules['enable'][ $key ] ),
							'wcol_max_order_limit' => '' !== stripcslashes( $raw_exclude_rules['rule-limit'][ $key ] ) ? stripslashes( $raw_exclude_rules['rule-limit'][ $key ] ) : 0,
						);
					}
				}
			}
			if ( isset( $data['settings-rules-changes'] ) ) {
				$data     = $this->wcol_sanitize( $data );
				$settings = $data;
			}

			if ( isset( $data['exculde_rules'] ) ) {
				$exludes = $wcol_exclude_rules;
			}
			$new_option = array(
				'wcol_settings'      => $settings,
				'wcol_exclude_rules' => $exludes,
			);
			$res        = update_option( 'wcol_options', $new_option );
			wp_send_json( $res );
		}
	}
	/**
	 * Add new row of rule.
	 *
	 * @since    3.0.0
	 */
	public function wcol_load_new_row() {
			ob_start();
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
			$weeks_array  = array(
				'monday'    => __( 'Monday', 'order-limit-for-woocommerce' ),
				'tuesday'   => __( 'Tuesday', 'order-limit-for-woocommerce' ),
				'wednesday' => __( 'Wednesday', 'order-limit-for-woocommerce' ),
				'thursday'  => __( 'Thursday', 'order-limit-for-woocommerce' ),
				'friday'    => __( 'Friday', 'order-limit-for-woocommerce' ),
				'saturday'  => __( 'Saturday', 'order-limit-for-woocommerce' ),
				'sunday'    => __( 'Sunday', 'order-limit-for-woocommerce' ),
			);
			//phpcs:ignore
			if( isset( $_POST['rule_type'] ) && !empty( $_POST['rule_type'] ) ) {
				//phpcs:ignore
				switch ( $_POST['rule_type'] ) {
					case 'product':
						include plugin_dir_path( __FILE__ ) . 'views/template-single-product-rule-row.php';
						break;
					case 'category':
						include plugin_dir_path( __FILE__ ) . 'views/template-single-product-cat-rule-row.php';
						break;
				}
			}
			$new_row = ob_get_clean();
			//phpcs:ignore
			echo $new_row;
			die();
	}
	/**
	 * Add product panel tab.
	 *
	 * @since    3.0.0
	 */
	public function wcol_product_data_panel_tab() {
		?>
		<li class="wcol_tab"><a href="#wcol_tab"><?php esc_html_e( 'WC Order Limit', 'order-limit-for-woocommerce' ); ?></a></li>
		<?php
	}

	/**
	 * Add product extra field.
	 *
	 * @since    3.0.0
	 */
	public function wcol_product_data_panel() {
		global $post;
		$plugin    = new WC_Order_Limit();
		$wcol_rule = new WC_Order_Limit_Rule( $plugin->get_plugin_name(), $plugin->get_version() );
		if ( isset( $post->ID ) ) {
			$wcol_rules = $wcol_rule->get_rules( $post->ID, 'products' );
		} else {
			$wcol_rules = array();
		}

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
		$weeks_array  = array(
			'monday'    => __( 'Monday', 'order-limit-for-woocommerce' ),
			'tuesday'   => __( 'Tuesday', 'order-limit-for-woocommerce' ),
			'wednesday' => __( 'Wednesday', 'order-limit-for-woocommerce' ),
			'thursday'  => __( 'Thursday', 'order-limit-for-woocommerce' ),
			'friday'    => __( 'Friday', 'order-limit-for-woocommerce' ),
			'saturday'  => __( 'Saturday', 'order-limit-for-woocommerce' ),
			'sunday'    => __( 'Sunday', 'order-limit-for-woocommerce' ),
		);
		include plugin_dir_path( __FILE__ ) . 'views/wc-order-limit-rule-single-product-views.php';
	}
	/**
	 * Add category extra field.
	 *
	 * @since    3.0.0
	 * @param array $term Data category.
	 */
	public function wcol_product_cat_fields( $term ) {
		$plugin = new WC_Order_Limit();

		$wcol_rule = new WC_Order_Limit_Rule( $plugin->get_plugin_name(), $plugin->get_version() );
		if ( isset( $term->term_id ) ) {
			$wcol_rules = $wcol_rule->get_rules( $term->term_id, 'categories' );
		} else {
			$wcol_rules = array();
		}

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
		$weeks_array  = array(
			'monday'    => __( 'Monday', 'order-limit-for-woocommerce' ),
			'tuesday'   => __( 'Tuesday', 'order-limit-for-woocommerce' ),
			'wednesday' => __( 'Wednesday', 'order-limit-for-woocommerce' ),
			'thursday'  => __( 'Thursday', 'order-limit-for-woocommerce' ),
			'friday'    => __( 'Friday', 'order-limit-for-woocommerce' ),
			'saturday'  => __( 'Saturday', 'order-limit-for-woocommerce' ),
			'sunday'    => __( 'Sunday', 'order-limit-for-woocommerce' ),
		);
		include plugin_dir_path( __FILE__ ) . 'views/wc-order-limit-rule-single-cat-views.php';
	}
	/**
	 * Save product extra field.
	 *
	 * @since    3.0.0
	 * @param string $post_id Id of rule.
	 */
	public function process_product_meta_wcol_tab( $post_id ) {
		$plugin    = new WC_Order_Limit();
		$wcol_rule = new WC_Order_Limit_Rule( $plugin->get_plugin_name(), $plugin->get_version() );
		$wcol_rule->save_wcol_options( $post_id, 'products' );
	}
	/**
	 * Save categories extra field.
	 *
	 * @since    3.0.0
	 * @param integer $term_id Id of category.
	 * @param string  $tt_id Id of taxonomy.
	 * @param string  $taxonomy Name of taxonomy.
	 */
	public function save_wcol_product_cat_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( 'product_cat' !== $taxonomy ) {
			return;
		}
		$plugin    = new WC_Order_Limit();
		$wcol_rule = new WC_Order_Limit_Rule( $plugin->get_plugin_name(), $plugin->get_version() );
		$wcol_rule->save_wcol_options( $term_id, 'categories' );
	}
	/**
	 * Delete rule.
	 *
	 * @since    3.0.0
	 */
	public function xswcol_delete_rule() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_wcol_save_rules_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wcol_save_rules_nonce'] ) ), 'wcol_save_rules' ) ) {
			return;
		}
		$rule_id = isset( $_POST['rule_id'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_id'] ) ) : '';
		if ( $rule_id ) {
			if ( get_post_meta( $rule_id, 'wcol-post-rules', true ) ) {
				wp_delete_post( $rule_id );
			}
			wp_send_json( array( 'status' => true ) );
			wp_die();
		} else {
			wp_send_json( array( 'status' => false ) );
			wp_die();
		}
	}
	/**
	 * Insert title of rule if empty.
	 *
	 * @since    3.0.0
	 * @param    array $data   Array of rule data.
	 * @param    array $postarr Detail post type rule.
	 */
	public function wcol_insert_posttitle( $data, $postarr ) {
		if ( 'wcol_rule' === $postarr['post_type'] ) {
			if ( empty( $data['post_title'] ) ) {
				$data['post_title'] = 'WCOL-' . $postarr['ID'];
			}
		}
		return $data;
	}
	/**
	 * Update view of rule.
	 *
	 * @since    3.0.0
	 * @param    array $views   Array of views.
	 */
	public function wcol_add_filter( $views ) {
		global $current_user, $wp_query, $wpdb;
		$rule_type = '';
		//phpcs:ignore
		if ( isset( $_GET['rule_type'] ) && ! empty( $_GET['rule_type'] ) ) {
			//phpcs:ignore
			$rule_type = sanitize_text_field( wp_unslash( $_GET['rule_type'] ) );
		}
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$result            = $wpdb->get_results( $wpdb->prepare( "SELECT p.ID FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id WHERE p.post_type = 'wcol_rule' AND  m.meta_key = 'wcol-rule-type' AND  m.meta_value = %s", 'products' ) );
		$class             = ( 'products' === $rule_type ) ? 'class="current"' : '';
		$views['products'] = sprintf( '<a href="%s" ' . $class . '>' . esc_html__( 'Products Rules ', 'order-limit-for-woocommerce' ) . '<span class="count">(%d)</span></a>', admin_url( 'edit.php?rule_type=products&post_type=wcol_rule' ), count( $result ) );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$result              = $wpdb->get_results( $wpdb->prepare( "SELECT p.ID FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id WHERE p.post_type = 'wcol_rule' AND  m.meta_key = 'wcol-rule-type' AND  m.meta_value =%s ", 'categories' ) );
		$class               = ( 'categories' === $rule_type ) ? ' class="current"' : '';
		$views['categories'] = sprintf( '<a href="%s" ' . $class . '>' . esc_html__( 'Categories Rules ', 'order-limit-for-woocommerce' ) . '<span class="count">(%d)</span></a>', admin_url( 'edit.php?rule_type=categories&post_type=wcol_rule' ), count( $result ) );
		return $views;
	}
	/**
	 * Update query of rule.
	 *
	 * @since    3.0.0
	 * @param    array $query   Array of query.
	 */
	public function wcol_filter_rule( $query ) {
		global $pagenow;
		//phpcs:ignore
		if ( is_admin() && isset( $_GET['rule_type'] ) && ! empty( $_GET['rule_type'] ) && isset( $_GET['post_type'] ) && 'wcol_rule' === $_GET['post_type'] ) {
			//phpcs:ignore
			$post_type = sanitize_text_field( wp_unslash( $_GET['rule_type'] ) );
			if ( 'edit.php' === $pagenow && '' !== $post_type ) {
				//phpcs:ignore
				$query->query_vars['meta_key']   = 'wcol-rule-type';
				//phpcs:ignore
				$query->query_vars['meta_value'] = $post_type;
			}
		}
	}
	/**
	 * Update message of rule post.
	 *
	 * @since    3.0.0
	 * @param    array $messages   Array of message.
	 */
	public function wcol_updated_messages( $messages ) {
		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages['wcol_rule'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => esc_html__( 'Rule updated.', 'order-limit-for-woocommerce' ),
			2  => esc_html__( 'Custom field updated.', 'order-limit-for-woocommerce' ),
			3  => esc_html__( 'Custom field deleted.', 'order-limit-for-woocommerce' ),
			4  => esc_html__( 'Rule updated.', 'order-limit-for-woocommerce' ),
			// translators: %s: date and time of the revision.
			//phpcs:ignore
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Rule restored to revision from %s', 'order-limit-for-woocommerce' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => esc_html__( 'Rule published.', 'order-limit-for-woocommerce' ),
			7  => esc_html__( 'Rule saved.', 'order-limit-for-woocommerce' ),
			8  => __( 'Rule submitted.', 'order-limit-for-woocommerce' ),
			9  => sprintf(
				esc_html__( 'Rule scheduled for: .', 'order-limit-for-woocommerce' ) . '<strong>%1$s</strong>',
				date_i18n( __( 'M j, Y @ G:i', 'order-limit-for-woocommerce' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Rule draft updated.', 'order-limit-for-woocommerce' ),
		);
		return $messages;
	}
	/**
	 * Add field variation limit data.
	 *
	 * @since    3.0.0
	 * @param    string $loop   Index of variation.
	 * @param    array  $variation_data  Array of variation data.
	 * @param    array  $variation Array of variations data.
	 */
	public function xswcol_variation_options( $loop, $variation_data, $variation ) {
		$is_checked = get_post_meta( $variation->ID, '_wcol_rule_limit', true );
		if ( 'yes' === $is_checked ) {
			$is_checked = 'checked';
		} else {
			$is_checked = '';
		}
		?>
		<label class="tips" data-tip="<?php esc_attr_e( 'Apply Min and max limit in variation', 'order-limit-for-woocommerce' ); ?>">
			<?php esc_html_e( 'Apply Min/Max limit', 'order-limit-for-woocommerce' ); ?>
			<input type="checkbox" data-id='<?php echo esc_attr( $loop ); ?>' class="checkbox variable_checkbox wcol_rule_checkbox" name="_wcol_rule_limit[<?php echo esc_attr( $loop ); ?>]"<?php echo esc_attr( $is_checked ); ?>/>
		</label>
		<?php
	}
	/**
	 * Add field variation limit data.
	 *
	 * @since    3.0.0
	 * @param    string $loop   Index of variation.
	 * @param    array  $variation_data  Array of variation data.
	 * @param    array  $variation Array of variations data.
	 */
	public function xswcol_add_limit_field_to_variations( $loop, $variation_data, $variation ) {
		$is_checked = get_post_meta( $variation->ID, '_wcol_rule_limit', true );
		wp_nonce_field( 'wcol_save_rules', '_wcol_save_rules_nonce', true );
		if ( 'yes' === $is_checked ) {
			$class = '';
		} else {
			$class = 'wcol-hidden';
		}
		woocommerce_wp_text_input(
			array(
				'id'            => 'wcol_min_limit_' . $loop,
				'class'         => 'short ',
				'wrapper_class' => $class,
				'label'         => __( 'Minimum Limit', 'order-limit-for-woocommerce' ),
				'value'         => get_post_meta( $variation->ID, 'wcol_min_limit', true ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'            => 'wcol_max_limit_' . $loop,
				'class'         => 'short ',
				'wrapper_class' => $class,
				'label'         => __( 'Maximum Limit', 'order-limit-for-woocommerce' ),
				'value'         => get_post_meta( $variation->ID, 'wcol_max_limit', true ),
			)
		);
	}
	/**
	 * Add variation limit data.
	 *
	 * @since    3.0.0
	 * @param    array $variations Array of variations data.
	 */
	public function xswcol_add_limit_field_variation_data( $variations ) {
		$variations['wcol_min_limit'] = '<div class="woocommerce_wcol_min_limit">Minimum limit: <span>' . get_post_meta( $variations['variation_id'], 'wcol_min_limit', true ) . '</span></div>';
		$variations['wcol_max_limit'] = '<div class="woocommerce_wcol_min_limit">Maximum limit: <span>' . get_post_meta( $variations['variation_id'], 'wcol_min_limit', true ) . '</span></div>';
		return $variations;
	}
	/**
	 * Add Column of rule id.
	 *
	 * @since    3.0.0
	 * @param    string $variation_id Id of variation.
	 * @param    string $i index of variation limit.
	 */
	public function xswcol_save_product_variation( $variation_id, $i ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_wcol_save_rules_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wcol_save_rules_nonce'] ) ), 'wcol_save_rules' ) ) {
			return;
		}
		if ( ! empty( $_POST['_wcol_rule_limit'] ) && ! empty( $_POST['_wcol_rule_limit'][ $i ] ) ) {
			update_post_meta( $variation_id, '_wcol_rule_limit', 'yes' );
		} else {
			update_post_meta( $variation_id, '_wcol_rule_limit', 'no' );
		}
		$wcol_min_limit = 0;
		if ( isset( $_POST[ 'wcol_min_limit_' . $i ] ) && ! empty( $_POST[ 'wcol_min_limit_' . $i ] ) ) {
			$wcol_min_limit = sanitize_text_field( wp_unslash( $_POST[ 'wcol_min_limit_' . $i ] ) );
		}
		if ( isset( $wcol_min_limit ) ) {
			update_post_meta( $variation_id, 'wcol_min_limit', esc_attr( $wcol_min_limit ) );
		}
		$wcol_max_limit = 0;
		if ( isset( $_POST[ 'wcol_max_limit_' . $i ] ) && ! empty( $_POST[ 'wcol_max_limit_' . $i ] ) ) {
			$wcol_max_limit = sanitize_text_field( wp_unslash( $_POST[ 'wcol_max_limit_' . $i ] ) );
		}
		if ( isset( $wcol_max_limit ) ) {
			update_post_meta( $variation_id, 'wcol_max_limit', esc_attr( $wcol_max_limit ) );
		}
	}
	/**
	 * Add Column of rule id.
	 *
	 * @since    3.0.0
	 * @param    array $columns Array of cloumn.
	 */
	public function set_custom_edit_wcol_rule_columns( $columns ) {
		$columns['rule_id'] = __( 'ID', 'order-limit-for-woocommerce' );
		return $columns;
	}
	/**
	 * Column rule id.
	 *
	 * @since    3.0.0
	 * @param    string $column      Index of cloumn.
	 * @param    string $post_id   Id of post.
	 */
	public function custom_wcol_rule_column( $column, $post_id ) {
		switch ( $column ) {
			case 'rule_id':
				echo esc_html( $post_id );
				break;
		}
	}
}
