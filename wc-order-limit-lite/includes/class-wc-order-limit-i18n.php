<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://xfinitysoft.com/
 * @since      3.0.0
 *
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      3.0.0
 * @package    WC_Order_Limit
 * @subpackage WC_Order_Limit/includes
 * @author     xfinitysoft <support@xfinitysoft.com>
 */
class WC_Order_Limit_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    3.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'order-limit-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
