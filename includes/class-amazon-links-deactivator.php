<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://codeable.io/developers/sofiane-achouba
 * @since      1.0.0
 *
 * @package    Amazon_Links
 * @subpackage Amazon_Links/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Amazon_Links
 * @subpackage Amazon_Links/includes
 * @author     Sofiane Achouba < >
 */
class Amazon_Links_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		global $wpdb;

		$timestamp = wp_next_scheduled('amazon_links_cron_hook');
		wp_unschedule_event($timestamp, 'amazon_links_cron_hook');

		$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'amazon_link'");
		$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
	}
}
