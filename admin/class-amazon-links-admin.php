<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codeable.io/developers/sofiane-achouba
 * @since      1.0.0
 *
 * @package    Amazon_Links
 * @subpackage Amazon_Links/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Amazon_Links
 * @subpackage Amazon_Links/admin
 * @author     Sofiane Achouba < >
 */
class Amazon_Links_Admin
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Amazon_Links_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Amazon_Links_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/amazon-links-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Amazon_Links_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Amazon_Links_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/amazon-links-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * cron function.
	 *
	 * @since    1.0.0
	 */
	function amazon_links_cron_hook_func()
	{
		// code de la fonction a executer dans la tache cron

	}

	/**
	 * Amazon links CPT  init.
	 *
	 * @since    1.0.0
	 */
	public function amazon_link_cpt()
	{
		register_post_type('amazon_link', array(
			'public' => true,
			'menu_position' => 30,
			'menu_icon' => 'dashicons-tagcloud',
			'labels' => array(
				'name' => __('Amazon Links', 'amazon-links'),
				'singular_name ' => __('Amazon Link', 'amazon-links'),
				'menu_name' => __('Amazon Links', 'amazon-links'),
			),
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'public' => false,
			'show_ui' => true,
			'has_archive' => false,
			'capabilities' => array(
				'create_posts' => false,
				'read_post' => 'manage_options',
				'delete_post' => 'manage_options',
				'edit_others_posts' => false,
				'publish_posts' => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
		));
	}

	/**
	 * add custom colun  content box id (header)
	 *
	 * @since    1.0.0
	 */
	public function add_custom_column($columns)
	{


		$arr = array(
			'cb' => $columns['cb'],
			"title" => __('Title'),
			"the_amazon_link" => __('Link')
		);
		return $arr;
	}
}
