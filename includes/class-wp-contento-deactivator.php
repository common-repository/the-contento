<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.contento.com.ng
 * @since      1.0.0
 *
 * @package    Wp_Contento
 * @subpackage Wp_Contento/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Contento
 * @subpackage Wp_Contento/includes
 * @author     Femtosh Global Solutions <info@femtosh.com>
 */
class Wp_Contento_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        $timestamp = wp_next_scheduled( 'contento_cron' );
        wp_unschedule_event( $timestamp, 'contento_cron' );

    }

}
