<?php
/**
 * @package          Flexie-CRM
 * @link              https://flexie.io/
 * @since             1.0.0
 * @flexie-crm
 * Plugin Name:       Flexie WooCommerce Integration
 * Plugin URI:        https://github.com/flexie-crm/WooCommerce-Tracking
 * Description:       Flexie CRM Integration with WooCommerce
 * Version:           1.0.0
 * Author:            Flexie CRM
 * Author URI:        https://flexie.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       flexie-crm
 */
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2019 Flexie CRM.
*/

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Check if called directly
 *
 */
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/**
 * Check if WooCommerce is active
 *
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

/**
 * Define Plugin Directory
 *
 */
define( 'FLEXIE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Require main classes
 *
 */
require_once( FLEXIE__PLUGIN_DIR . 'class.flexie.php' );
require_once( FLEXIE__PLUGIN_DIR . 'class.loadtrack.php' );
require_once( FLEXIE__PLUGIN_DIR . 'class.flexie-register-settings.php' );

/**
 * Initialise WooCommerce Webhooks
 *
 */
add_action( 'init', array( 'Flexie', 'init' ), 0 );	

/**
 * Initialise Plugin Settings in Wordpress
 *
 */
$initSettings = new Flexie_Register_Settings();
$initSettings->Register_Settings();
}


    