<?php

/**
 * Deals shortcode.
 *
 * @global wpdb               $wpdb
 * @global Orbis_Deals_Plugin $orbis_deals_plugin
 *
 * @param array $attributes
 *
 * @return string
 */
function orbis_deals_shortcode_orbis_deals( $attributes ) {

	global $wpdb;
	global $orbis_deals_plugin;

	$available_statuses = array_keys( orbis_deal_get_statuses() );
	$statuses           = $available_statuses;

	if ( isset( $attributes['status'] ) && in_array( $attributes['status'], $available_statuses ) ) {
		$statuses = array( $attributes['status'] );
	}

	$query = call_user_func_array( array( $wpdb, 'prepare' ), array_merge(
        array(
	        "
	        SELECT
	            d.id,
	            d.price,
	            d.status,
	            c.name AS company_name
			FROM
				$wpdb->orbis_deals AS d
					LEFT JOIN
				$wpdb->orbis_companies AS c
					ON d.company_id = c.id
			WHERE
				d.status IN ( %d" . str_repeat( ', %d', count( $statuses ) - 1 ) . " )
		    "
        ),
		$statuses
	) );

	$data          = new stdClass();
	$data->results = $wpdb->get_results( $query );

	ob_start();

	$orbis_deals_plugin->plugin_include( 'templates/deals.php', array( 'data' => $data ) );

	return ob_get_clean();
}

add_shortcode( 'orbis_deals', 'orbis_deals_shortcode_orbis_deals' );