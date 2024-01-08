<?php
/**
 * Orbis Deals
 *
 * @package   Pronamic\Orbis\Deals
 * @author    Pronamic
 * @copyright 2024 Pronamic
 * @license   GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Orbis Deals
 * Plugin URI:        https://wp.pronamic.directory/plugins/orbis-deals/
 * Description:       The Orbis Deals plugin extends your Orbis environment with the option to add deals.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pronamic
 * Author URI:        https://www.pronamic.eu/
 * Text Domain:       orbis-deals
 * Domain Path:       /languages/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://wp.pronamic.directory/plugins/orbis-deals/
 * GitHub URI:        https://github.com/pronamic/wp-orbis-deals
 */

/**
 * Autoload.
 */
require_once __DIR__ . '/vendor/autoload_packages.php';

/**
 * Bootstrap.
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

/**
 * Bootstrap.
 */
add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain( 'orbis-deals', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}
);
