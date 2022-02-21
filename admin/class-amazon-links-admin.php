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
		global $wpdb;
		//delete all old ones 
		$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'amazon_link';");
		$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts);");

		//call to checking
		$this->generate_amazon_links();
	}

	//function to get all links with short codes
	public function get_link_by_short_code($content)
	{
		$shortcode_regex = '/[^\]]+(?=\[\/asa2])/i';
		$links = array();
		preg_match_all($shortcode_regex, $content, $short_codes);
		foreach ($short_codes[0] as $short_code) {
			$links[] = "https://www.amazon.es/dp/" . $short_code;
		}
		return $links;
	}

	//function to get all links from content
	function get_urls($content)
	{
		$link_regex = '/https:\/\/(www\.)*am[za].*?(?=[?\'\"])/i';
		preg_match_all($link_regex, $content, $links);
		return $links[0];
	}

	/**
	 * checking and updating links in post types.
	 *
	 * @since    1.0.0
	 */
	public static function generate_amazon_links()
	{
		// check the links in all posts
		$all_posts = get_posts(array(
			"post_type" => "post",
			'post_status' => 'publish',
			'posts_per_page' => -1
		));

		foreach ($all_posts as $post) {
			$final_links = array();
			$content_links = self::get_urls($post->post_content);
			$shortcode_links = self::get_link_by_short_code($post->post_content);
			$amazon_link = array(
				'post_title'    => $post->post_title,
				'post_type'  => 'amazon_link',
				'post_status'   => 'publish'
			);

			// get all links
			$final_links = array_values(array_unique(array_merge($content_links, $shortcode_links)));

			// No amazon links in this post
			if (count($final_links) === 0) continue;

			//store all post links in one amazon link post 
			$post_id = wp_insert_post($amazon_link);
			update_post_meta($post_id, "the_amazon_link", $final_links);
		}
	}


	/**
	 * Amazon links CPT  init.
	 *
	 * @since    1.0.0
	 */
	public static function amazon_link_cpt()
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
			'show_ui' => true,
			'has_archive' => false,
			'capabilities' => array(
				'create_posts' => false,
				'read_post' => 'manage_options',
				'delete_post' => false,
				'edit_others_posts' => false,
				'publish_posts' => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
		));
	}

	/**
	 * add custom link columns  amazon links 
	 *
	 * @since    1.0.0
	 */
	public function add_link_columns($columns)
	{
		$columns = array(
			"title" => __('Title'),
			"the_amazon_link" => __('Link'),
		);
		return $columns;
	}

	/**
	 * customize link columns  (amazon links)
	 * @since    1.0.0
	 */
	public function link_columns_data($column, $post_id)
	{

		if ($column == "the_amazon_link") {
			$links = get_post_meta($post_id, "the_amazon_link", true);
			foreach ($links as $link) {
				echo "<strong>$link</strong><br />";
			}
		}
	}
}
