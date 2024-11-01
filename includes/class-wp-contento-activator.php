<?php

/**
 * Fired during plugin activation
 *
 * @link       www.contento.com.ng
 * @since      1.0.0
 *
 * @package    Wp_Contento
 * @subpackage Wp_Contento/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Contento
 * @subpackage Wp_Contento/includes
 * @author     Femtosh Global Solutions <info@femtosh.com>
 */
class Wp_Contento_Activator
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
        if (!wp_next_scheduled('contento_cron')) {
            wp_schedule_event(time(), '5seconds', 'contento_cron');
        }
    }

}
