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

		$timestamp = wp_next_scheduled('custom_cron_hook');
		wp_unschedule_event($timestamp, 'custom_cron_hook');
	}
}
