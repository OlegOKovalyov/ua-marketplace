<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_ua_marketplace_plugin() {
	Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_ua_marketplace_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_ua_marketplace_plugin() {
	Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_ua_marketplace_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'Inc\\Init' ) ) {
	Inc\Init::register_services();
}
