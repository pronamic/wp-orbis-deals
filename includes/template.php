<?php

/**
 * Render a deal's details.
 */
function orbis_deals_render_details() {

	if ( is_singular( 'orbis_deal' ) ) {

		global $orbis_deals_plugin;

		$orbis_deals_plugin->plugin_include( 'templates/deal-details.php' );
	}
}

add_action( 'orbis_before_side_content', 'orbis_deals_render_details' );