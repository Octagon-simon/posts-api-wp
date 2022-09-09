<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://octagon-simon.github.io
 * @since      1.0.0
 *
 * @package    Posts_Api_Wp
 * @subpackage Posts_Api_Wp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Posts_Api_Wp
 * @subpackage Posts_Api_Wp/includes
 * @author     Simon Ugorji <ugorji757@gmail.com>
 */
class Posts_Api_Wp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'posts-api-wp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
