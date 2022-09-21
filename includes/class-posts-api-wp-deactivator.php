<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://octagon-simon.github.io/projects/posts-api-wp/
 * @since      1.0.0
 *
 * @package    Posts_Api_Wp
 * @subpackage Posts_Api_Wp/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Posts_Api_Wp
 * @subpackage Posts_Api_Wp/includes
 * @author     Simon Ugorji <ugorji757@gmail.com>
 */
class Posts_Api_Wp_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		//revoke api key but retain configuration options
		$configOpts = (get_option('posts_api_wp_config')) ? json_decode(get_option('posts_api_wp_config')) : null;
		if ($configOpts) {
			//revoke auth key
			unset($configOpts->authKey);
			//update config
			update_option('posts_api_wp_config', json_encode($configOpts));
		}
	}

}
