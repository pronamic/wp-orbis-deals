<?php

/**
 * Get array of available statuses.
 *
 * @return array
 */
function orbis_deal_get_statuses() {
	return array(
		'pending' => __( 'Pending', 'orbis-deals' ),
		'won'     => __( 'Won', 'orbis-deals' ),
		'lost'    => __( 'Lost', 'orbis-deals' ),
	);
}

function orbis_deal_get_status_label( $status ) {
	$statuses = orbis_deal_get_statuses();

	$label = null;

	if ( isset( $statuses[ $status ] ) ) {
		$label = $statuses[ $status ];
	}

	return $label;
}
