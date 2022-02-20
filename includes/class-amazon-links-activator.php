<?php

require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-amazon-links-admin.php';

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
			wp_schedule_event(time(), 'twicedaily', 'amazon_links_cron_hook');
		}
		Amazon_Links_Admin::amazon_link_cpt();
		Amazon_Links_Admin::generate_amazon_links();
	}
}
