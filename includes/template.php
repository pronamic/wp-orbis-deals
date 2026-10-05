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

/**
 * Template include.
 *
 * Uses the archive deal template of this plugin, unless the theme has one.
 *
 * @param string $template Template.
 * @return string
 */
function orbis_deals_template_include( $template ) {
	if ( is_post_type_archive( 'orbis_deal' ) && '' === locate_template( 'archive-orbis_deal.php' ) ) {
		return __DIR__ . '/../templates/archive-orbis_deal.php';
	}

	return $template;
}

add_filter( 'template_include', 'orbis_deals_template_include' );
