<?php 
/**
 * Plugin Name: WooCommerce Product's Extra Addons
 * Description: Add Product's Extra Addons options on the product details page
 * Plugin URI: https://
 * Author: Mak Alamin
 * Author URI: https://
 * Version: 1.0.0
 * Text Domain: woo-products-extra-addons
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if( ! defined('ABSPATH') ){
    exit;
}

// Require once the Composer Autoload
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Define Constants
if(!defined('SC_EXTRA_ADDONS_ASSETS')){
    define('SC_EXTRA_ADDONS_ASSETS', plugin_dir_url(__FILE__) . 'assets/');
}

/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('WooExtraAddonsInc\\Init')) {
    WooExtraAddonsInc\Init::registerServices();
}