<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://profiles.wordpress.org/rahulharkhani/
 * @since      1.0.0
 *
 * @package    Woocommerce_Quick_Order
 * @subpackage Woocommerce_Quick_Order/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Quick_Order
 * @subpackage Woocommerce_Quick_Order/includes
 * @author     Rahul Harkhani <rahul.harkhani11@gmail.com>
 */
class Woocommerce_Quick_Order_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-quick-order',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
