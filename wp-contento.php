<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.contento.com.ng
 * @since             1.0.0
 * @package           Wp_Contento
 *
 * @wordpress-plugin
 * Plugin Name:       The Contento
 * Plugin URI:        http://www.contento.com.ng
 * Description:       The plugin leverages contento.com.ng to provide automatic contents for wordpress sites. It also manages contents autoplublishing.
 * Version:           1.2
 * Author:            Femtosh Global Solutions
 * Author URI:        http://www.femtosh.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-contento
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-contento-activator.php
 */
function activate_wp_contento() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-contento-activator.php';
	Wp_Contento_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-contento-deactivator.php
 */
function deactivate_wp_contento() {
     require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-contento-deactivator.php';
	Wp_Contento_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_contento' );
register_deactivation_hook( __FILE__, 'deactivate_wp_contento' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-contento.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_contento() {

	$plugin = new Wp_Contento();
	$plugin->run();

}
run_wp_contento();
