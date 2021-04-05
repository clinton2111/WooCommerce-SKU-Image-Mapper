<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codemarketing.com
 * @since             1.0.0
 * @package           Cm_Prod_Img_Map
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Product Image Mapper
 * Plugin URI:        https://codemarketing.com
 * Description:       Map images from the gallery to products based on the SKUs or add a placeholder image.
 * Version:           1.0.0
 * Author:            Clinton D
 * Author URI:        https://codemarketing.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cm-prod-img-map
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CM_PROD_IMG_MAP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cm-prod-img-map-activator.php
 */
function activate_cm_prod_img_map() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cm-prod-img-map-activator.php';
	Cm_Prod_Img_Map_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cm-prod-img-map-deactivator.php
 */
function deactivate_cm_prod_img_map() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cm-prod-img-map-deactivator.php';
	Cm_Prod_Img_Map_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cm_prod_img_map' );
register_deactivation_hook( __FILE__, 'deactivate_cm_prod_img_map' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cm-prod-img-map.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cm_prod_img_map() {

	$plugin = new Cm_Prod_Img_Map();
	$plugin->run();

}
run_cm_prod_img_map();
