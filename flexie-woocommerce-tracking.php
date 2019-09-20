<?php
/**
 * @package          Flexie-WooCommerce-Tracking
 * @link             https://flexie.io/
 * @since            1.0.0
 * @flexie-crm
 * Plugin Name:      Flexie WooCommerce Tracking
 * Plugin URI:       https://github.com/flexie-crm/WooCommerce-Tracking
 * Description:      Flexie CRM WooCommerce Tracking
 * Version:          1.0.0
 * Author:           Flexie CRM
 * Author URI:       https://flexie.io
 * License:          MIT
 * License URI:      https://opensource.org/licenses/MIT
 * Text Domain:      Flexie-WooCommerce-Tracking
 */

/*
MIT License

Copyright (c) 2019 Flexie CRM

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
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


    
