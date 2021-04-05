<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://codemarketing.com
 * @since      1.0.0
 *
 * @package    Cm_Prod_Img_Map
 * @subpackage Cm_Prod_Img_Map/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cm_Prod_Img_Map
 * @subpackage Cm_Prod_Img_Map/includes
 * @author     Clinton D <3950377+clinton2111@users.noreply.github.com>
 */
class Cm_Prod_Img_Map_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cm-prod-img-map',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
