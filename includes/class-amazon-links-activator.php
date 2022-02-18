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
 * @author     Sofiane Achouba < >
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
		if (!wp_next_scheduled('custom_cron_hook')) {
			wp_schedule_event(time(), 'hourly', 'custom_cron_hook');
		}
	}
}
