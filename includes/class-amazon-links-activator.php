<?php

/**
 * Fired during plugin activation
 *
 * @link       https://codeable.io/developers/sofiane-achouba
 * @since      1.0.0
 *
 * @package    Amazon_Links
 * @subpackage Amazon_Links/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Amazon_Links
 * @subpackage Amazon_Links/includes
 * @author     Sofiane Achouba <a >
 */
class Amazon_Links_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		//creating cron job 
		if (!wp_next_scheduled('amazon_links_cron_hook')) {
			wp_schedule_event(time(), 'hourly', 'amazon_links_cron_hook');
		}


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
				'edit_posts' => false,
				'edit_others_posts' => false,
				'publish_posts' => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
		));


		$all_posts = get_posts([
			"post_type" => "post",
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order_by' => 'ID',
			'order' => 'ASC',
		]);
		//function to get all links from content
		function get_urls($string)
		{
			$regex = '/https:\/\/(www\.)*am[za].*?(?=[?\'\"])/i';
			preg_match_all($regex, $string, $matches);
			return ($matches[0]);
		}
		//function to get all links with short codes
		function get_link_by_short_code($string)
		{
			$regex = '/[^\]]+(?=\[\/asa2])/i';
			$links = array();
			preg_match_all(
				$regex,
				$string,
				$matches
			);
			foreach ($matches[0] as $short_code) {
				$links[] = "https://www.amazon.es/dp/" . $short_code;
			}
			return ($links);
		}

		foreach ($all_posts as $post) {

			$final_links = array();
			$content_links = get_urls($post->post_content);
			$shortcode_links = get_link_by_short_code($post->post_content);
			$mypost = [
				'post_title'    => $post->post_title,
				'post_type'  => 'amazon_link',
				'post_status'   => 'publish',
			];

			//checking for only amazon links
			foreach ($content_links as $link) {

				$final_links[] = $link;
			}
			//checking for shortcode
			foreach ($shortcode_links as $link) {

				$final_links[] = $link;
			}


			//creating for each amazon link a post
			foreach (array_unique($final_links) as $final_link) {
				$id = wp_insert_post($mypost);
				update_post_meta($id, "the_amazon_link", $final_link);
			}
		}
	}
}
