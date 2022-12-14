<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://octagon-simon.github.io/projects/posts-api-wp/
 * @since             1.0.0
 * @package           Posts_Api_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Posts API WP
 * Plugin URI:        https://octagon-simon.github.io/projects/posts-api-wp/
 * Description:       Posts API WP is a plugin that helps to make your WordPress posts available outside your WordPress blog. This plugin creates a virtual API that can be used to share your wordpress posts to other websites.
 * Version:           1.0.2
 * Author:            Simon Ugorji
 * Author URI:        https://octagon-simon.github.io/projects/posts-api-wp/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       posts-api-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('POSTS_API_WP_VERSION', '1.0.2');
define('POSTS_API_WP_PLUGIN_FILE', __FILE__);
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-posts-api-wp-activator.php
 */
function activate_posts_api_wp()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-posts-api-wp-activator.php';
	Posts_Api_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-posts-api-wp-deactivator.php
 */
function deactivate_posts_api_wp()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-posts-api-wp-deactivator.php';
	Posts_Api_Wp_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_posts_api_wp');
register_deactivation_hook(__FILE__, 'deactivate_posts_api_wp');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-posts-api-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_posts_api_wp()
{

	$plugin = new Posts_Api_Wp();
	$plugin->run();

}
run_posts_api_wp();

//add plugin links
function pawp_plugin_links($links, $file)
{
	$base = plugin_basename(POSTS_API_WP_PLUGIN_FILE);
	if ($file == $base) {
		$links[] = '<a href="https://twitter.com/ugorji_simon/" title="Follow me on Twitter"><i class="dashicons dashicons-twitter"></i></a>';
		$links[] = '<a href="https://fb.com/simon.ugorji.106" title="Follow me on Facebook"><i class="dashicons dashicons-facebook"></i></a>';
		$links[] = '<a href="https://www.linkedin.com/in/simon-ugorji-57a6a41a3/" title="Connect With Me on linkedin"><i class="dashicons dashicons-linkedin"></i></a>';
		$links[] = '<a href="https://www.paypal.com/donate/?hosted_button_id=ZYK9PQ8UFRTA4" title="Donate"><i class="dashicons dashicons-coffee"></i></a>';
	}
	return $links;
}

add_filter('plugin_row_meta', 'pawp_plugin_links', 10, 2);

//virtual pages
class PAWPVirtualPage
{
	function __construct()
	{

		register_activation_hook(__FILE__, array($this, 'activate'));

		add_action('init', array($this, 'rewrite'));
		add_filter('query_vars', array($this, 'query_vars'));
		add_action('template_include', array($this, 'change_template'));

	}

	function activate()
	{
		set_transient('pawpvp_flush', 1, 60);
	}

	function rewrite()
	{
		//add_rewrite_endpoint( 'dump', EP_PERMALINK );
		//add_rewrite_rule( '^the-page$', 'index.php?vptutorial=1', 'top' );
		add_rewrite_rule('^posts-api-wp$', 'index.php?postsapiwp=1', 'top');

		if (get_transient('pawpvp_flush')) {
			delete_transient('pawpvp_flush');
			flush_rewrite_rules();
		}
	}

	function query_vars($vars)
	{
		$vars[] = 'postsapiwp';

		return $vars;
	}

	function change_template($template)
	{
		if (get_query_var('postsapiwp', false) !== false) {

			$newTemplate = locate_template(array('template-get-posts.php'));
			if ('' != $newTemplate)
				return $newTemplate;

			//Check plugin directory next
			$newTemplate = plugin_dir_path(__FILE__) . 'templates/template-get-posts.php';
			if (file_exists($newTemplate))
				return $newTemplate;
		}

		//Fall back to original template
		return $template;

	}

}
//instantiate class
new PAWPVirtualPage;