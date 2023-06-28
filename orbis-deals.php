<?php
/*
Plugin Name: Orbis Deals
Plugin URI: http://www.happywp.com/plugins/orbis-deals/
Description: The Orbis Deals plugin extends your Orbis environment with the option to add deals.

Version: 1.0.0
Requires at least: 3.5

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: orbis_deals
Domain Path: /languages/

License: Copyright (c) Pronamic

GitHub URI: https://github.com/pronamic/wp-orbis-deals
*/

function orbis_deals_bootstrap() {
	// Classes
	require_once 'classes/orbis-deals-plugin.php';

	// Functions
	require_once 'includes/functions.php';

	// Initialize
	global $orbis_deals_plugin;

	$orbis_deals_plugin = new Orbis_Deals_Plugin( __FILE__ );

	// REST API
	require_once 'includes/rest.php';

	$rest_controller = new Orbis_Deals_RestController();

	add_action( 'rest_api_init', [ $rest_controller, 'rest_api_init' ] );
}

add_action( 'plugins_loaded', 'orbis_deals_bootstrap' );
