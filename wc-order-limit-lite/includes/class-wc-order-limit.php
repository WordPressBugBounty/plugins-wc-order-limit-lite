<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      3.0.0
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/includes
 * @author     xfinitysoft <support@xfinitysoft.com>
 */
class WC_Order_Limit {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    3.0.0
	 * @access   protected
	 * @var      WC_Order_Limit_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    3.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    3.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    3.0.0
	 */
	public function __construct() {
		if ( defined( 'WC_ORDER_LIMIT_VERSION' ) ) {
			$this->version = WC_ORDER_LIMIT_VERSION;
		} else {
			$this->version = '3.0.0';
		}
		$this->plugin_name = 'order-limit-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WC_Order_Limit_Loader. Orchestrates the hooks of the plugin.
	 * - WC_Order_Limit_i18n. Defines internationalization functionality.
	 * - WC_Order_Limit_Admin. Defines all hooks for the admin area.
	 * - WC_Order_Limit_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wc-order-limit-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wc-order-limit-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-wc-order-limit-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-wc-order-limit-block.php';
		/**
		* The class responsible for defining all action  that apply rules.
		*/
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wc-order-limit-rule.php';

		$this->loader = new WC_Order_Limit_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WC_Order_Limit_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WC_Order_Limit_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WC_Order_Limit_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_block = new WC_Order_Limit_Block( $this->get_plugin_name(), $this->get_version() );
		$apply_rule   = new WC_Order_Limit_Rule( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'wcol_init', 10 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'woocommerce_get_settings_pages', $plugin_admin, 'wcol_woocommerce_settings_page' );
		$this->loader->add_action( 'rest_api_init', $plugin_block, 'wcol_register_block' );
		$this->loader->add_action( 'woocommerce_rest_insert_product_object', $plugin_block, 'wcol_save_api_rules', 200, 2 );
		$this->loader->add_action( 'init', $plugin_admin, 'wcol_create_post_type' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wcol_register_metabox' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wcol_save_metabox' );
		$this->loader->add_filter( 'wp_insert_post_data', $plugin_admin, 'wcol_insert_posttitle', 10, 2 );
		$this->loader->add_filter( 'views_edit-wcol_rule', $plugin_admin, 'wcol_add_filter' );
		$this->loader->add_filter( 'parse_query', $plugin_admin, 'wcol_filter_rule' );
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'wcol_updated_messages' );
		$this->loader->add_action( 'admin_head-post.php', $plugin_admin, 'wcol_add_plugin_notice' );
		$this->loader->add_action( 'wp_ajax_wc_order_limit_save_settings', $plugin_admin, 'wc_order_limit_save_settings' );
		$this->loader->add_action( 'wp_ajax_xswcol_delete_rule', $plugin_admin, 'xswcol_delete_rule' );
		$this->loader->add_action( 'woocommerce_product_write_panel_tabs', $plugin_admin, 'wcol_product_data_panel_tab' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'wcol_product_data_panel' );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'process_product_meta_wcol_tab', 10, 2 );
		$this->loader->add_action( 'woocommerce_variation_options', $plugin_admin, 'xswcol_variation_options', 10, 3 );
		$this->loader->add_action( 'woocommerce_variation_options_pricing', $plugin_admin, 'xswcol_add_limit_field_to_variations', 10, 3 );
		$this->loader->add_filter( 'woocommerce_available_variation', $plugin_admin, 'xswcol_add_limit_field_variation_data' );
		$this->loader->add_action( 'woocommerce_save_product_variation', $plugin_admin, 'xswcol_save_product_variation', 10, 2 );
		$this->loader->add_action( 'product_cat_add_form_fields', $plugin_admin, 'wcol_product_cat_fields', 10 );
		$this->loader->add_action( 'product_cat_edit_form_fields', $plugin_admin, 'wcol_product_cat_fields', 10 );
		$this->loader->add_action( 'created_term', $plugin_admin, 'save_wcol_product_cat_fields', 10, 3 );
		$this->loader->add_action( 'edit_term', $plugin_admin, 'save_wcol_product_cat_fields', 10, 3 );
		$this->loader->add_action( 'wp_ajax_wcol_load_new_row', $plugin_admin, 'wcol_load_new_row' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcol_load_new_row', $plugin_admin, 'wcol_load_new_row' );

		// checck for order limit rules on cart page and checkout page.

		$this->loader->add_action( 'woocommerce_before_cart', $apply_rule, 'wc_order_limit_check', 998 );
		$this->loader->add_action( 'woocommerce_checkout_process', $apply_rule, 'wc_order_limit_check', 999 );
		$this->loader->add_action( 'woocommerce_store_api_cart_errors', $apply_rule, 'wc_order_limit_check', 10 );

		// // Redirect to cart page if user not fulfil rules when visits checkout page.
		$this->loader->add_action( 'template_redirect', $apply_rule, 'wc_order_limit_restrict_checkout' );
		$this->loader->add_action( 'wp_enqueue_scripts', $apply_rule, 'wc_order_limit_footer_script' );

		$this->loader->add_filter( 'manage_wcol_rule_posts_columns', $plugin_admin, 'set_custom_edit_wcol_rule_columns' );
		$this->loader->add_action( 'manage_wcol_rule_posts_custom_column', $plugin_admin, 'custom_wcol_rule_column', 10, 2 );

		// Hooks get data products,categories and users.
		$this->loader->add_action( 'wp_ajax_wcol_get_product', $plugin_admin, 'wcol_get_product' );
		$this->loader->add_action( 'wp_ajax_wcol_get_categories', $plugin_admin, 'wcol_get_categories' );
		$this->loader->add_action( 'wp_ajax_wcol_get_users', $plugin_admin, 'wcol_get_users' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    3.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     3.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     3.0.0
	 * @return    WC_Order_Limit_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     3.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
