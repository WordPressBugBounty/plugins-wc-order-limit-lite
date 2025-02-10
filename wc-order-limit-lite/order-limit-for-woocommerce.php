<?php
/**
 * This is main plugin file.
 *
 * @link              http://xfinitysoft.com/
 * @since             3.0.0
 * @package           WC_Order_Limit
 *
 * Plugin Name: Order Limit For WooCommerce ( Free Version )
 * Description: Order Limit for WooCommerce allows you to set order limits based on products, categories, customers, time spans, order total, and payment methods during checkout.
 * Version: 3.0.1
 * Author: Xfinity Soft
 * Author URI: http://xfinitysoft.com/
 * Text Domain: order-limit-for-woocommerce
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 4.4.0
 * Tested up to: 6.7.1
 * WC requires at least: 3.0.0
 * WC tested up to: 9.6.1
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;
if ( ! defined( 'WCOL_MAIN_PLUGIN_FILE' ) ) {
	define( 'WCOL_MAIN_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'WCOL_MAIN_PLUGIN_DIR' ) ) {
	define( 'WCOL_MAIN_PLUGIN_DIR', __DIR__ );
}
/**
 * Currently plugin version.
 * Start at version 3.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WC_ORDER_LIMIT_VERSION', '3.0.0' );
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-order-limit-activator.php
 */
function activate_wc_order_limit() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'wc_order_limit_missing_wc_notice' );
		return;
	}
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-order-limit-activator.php';
	WC_Order_Limit_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_wc_order_limit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-order-limit.php';



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    3.0.0
 */
function run_wc_order_limit() {
	$plugin = new WC_Order_Limit();
	$plugin->run();
}
run_wc_order_limit();
/**
 * Woocommercer missing notice function.
 *
 * @since    3.0.0
 */
function wc_order_limit_missing_wc_notice() {
	/* translators: %s WC download URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Order Limit for woocommerce requires WooCommerce to be installed and active. You can download %s here.', 'order-limit-for-woocommerce' ), '<a href="https://woo.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}
