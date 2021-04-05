<?php

/**
 * Fired during plugin activation
 *
 * @link       https://codemarketing.com
 * @since      1.0.0
 *
 * @package    Cm_Prod_Img_Map
 * @subpackage Cm_Prod_Img_Map/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cm_Prod_Img_Map
 * @subpackage Cm_Prod_Img_Map/includes
 * @author     Clinton D <3950377+clinton2111@users.noreply.github.com>
 */
class Cm_Prod_Img_Map_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 *
	 */


	public static function activate() {
		if ( ! class_exists( 'WooCommerce' ) and current_user_can( 'activate_plugins' ) ) {
			$error_message = esc_html__( 'WooCommerce Product Image Mapper requires ', 'cm-prod-img-map' ) . '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '">WooCommerce</a>' . esc_html__( ' plugin to be active.', 'cm-prod-img-map' );
			die( $error_message );

			return false;
		}

		return true;
	}

}