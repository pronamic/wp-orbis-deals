<?php

/**
 * File for generate functions for deal usages
 * 
 * @author Leon Rowland <leon@rowland.nl>
 * @author Remco Tolsma <remco@pronamic.nl>
 */

if ( ! function_exists( 'orbis_deal_get_data' ) ) {
	
	/**
	 * Returns a row from the orbis_deals table
	 * where the post_id matches the parameter
	 * 
	 * @global wpdb $wpdb
	 * 
	 * @param int $post_id
	 * @return object
	 */
	function orbis_deal_get_data( $post_id ) {
		global $wpdb;
		
		$query = "
			SELECT
				d.id,
				d.post_id,
				d.price,
				d.status,
				c.id as company_id,
				c.post_id as company_post_id,
				c.name as company_name,
				c.e_mail as company_email
			FROM
				$wpdb->orbis_deals as d
					LEFT JOIN
				$wpdb->orbis_companies as c
						ON d.company_id = c.id
			WHERE
				d.post_id = %d
		";
		
		$query =  $wpdb->prepare( $query, $post_id );

		return $wpdb->get_row( $query );
	}
}

if ( ! function_exists( 'orbis_deal_get_statuses') ) {

	/**
	 * Get array of available statuses.
	 *
	 * @return array
	 */
	function orbis_deal_get_statuses() {

		return array(
			0 => __( 'Pending', 'orbis_deals' ),
			1 => __( 'Won'    , 'orbis_deals' ),
			2 => __( 'Lost'   , 'orbis_deals' ),
		);
	}
}