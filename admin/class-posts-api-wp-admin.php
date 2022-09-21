<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://octagon-simon.github.io/projects/posts-api-wp/
 * @since      1.0.0
 *
 * @package    Posts_Api_Wp
 * @subpackage Posts_Api_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Posts_Api_Wp
 * @subpackage Posts_Api_Wp/admin
 * @author     Simon Ugorji <ugorji757@gmail.com>
 */
class Posts_Api_Wp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		//add admin menu			
		add_action('admin_menu', array($this, 'BuildAdminMenu'), 9);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Posts_Api_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Posts_Api_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/posts-api-wp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Posts_Api_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Posts_Api_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/posts-api-wp-admin.js', array( 'jquery' ), $this->version, false );

	}

	//add plugin menu
	public function BuildAdminMenu()
	{
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page(__( $this->plugin_name, 'Posts API WP' ), 'Posts API WP', 'administrator', $this->plugin_name, array($this, 'GetStartedPage'), plugins_url( '/img/', __FILE__).'logo-menu.png', 25);

		//add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page($this->plugin_name, 'Posts API WP | Configure API', 'Configure API', 'administrator', $this->plugin_name . '-config-api', array($this, 'ConfigApiPage'));

		add_submenu_page($this->plugin_name, 'Posts API WP | How To Use', 'How To Use', 'administrator', $this->plugin_name . '-how-to-use', array($this, 'HowToUsePage'));

	}
	//get started page
	public function GetStartedPage()
	{
		require(dirname(__FILE__) . '/pages/get-started.html');
		require(dirname(__FILE__) . '/pages/footer.html');
	}
	//build api page
	public function ConfigApiPage()
	{
		require(dirname(__FILE__) . '/pages/config-api.php');
		require(dirname(__FILE__) . '/pages/footer.html');
	}

	public function HowToUsePage()
	{
		require(dirname(__FILE__) . '/pages/how-to-use.php');
		require(dirname(__FILE__) . '/pages/footer.html');
	}

}
