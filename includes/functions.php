<?php

/**
 * Get array of available statuses.
 *
 * @return array
 */
function orbis_deal_get_statuses() {
	return array(
		'pending' => __( 'Pending', 'orbis_deals' ),
		'won'     => __( 'Won'    , 'orbis_deals' ),
		'lost'    => __( 'Lost'   , 'orbis_deals' ),
	);
}
