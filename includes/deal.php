<?php

/**
 * Posts clauses
 *
 * http://codex.wordpress.org/WordPress_Query_Vars
 * http://codex.wordpress.org/Custom_Queries
 *
 * @param array    $pieces
 * @param WP_Query $query
 *
 * @return array $pieces
 */
function orbis_deals_posts_clauses( $pieces, $query ) {

	global $wpdb;

	$post_type = $query->get( 'post_type' );

	if ( $post_type == 'orbis_deal' ) {

		// Fields
		$fields = ",
			deal.price AS deal_price,
			deal.status AS deal_status,
			company.name AS company_name
		";

		// Join
		$join = "
			JOIN
				$wpdb->orbis_deals AS deal
					ON $wpdb->posts.ID = deal.post_id
			LEFT JOIN
				$wpdb->orbis_companies AS company
					ON deal.company_id = company.id
		";

		$status = filter_input( INPUT_GET, 'orbis_deal_status', FILTER_SANITIZE_STRING );

		// Where
		$where = $wpdb->prepare(
			"
				AND
			deal.status = %s
			",
			$status
		);

		$pieces['join']   .= $join;
		$pieces['fields'] .= $fields;
		$pieces['where']  .= $where;
	}

	return $pieces;
}

add_filter( 'posts_clauses', 'orbis_deals_posts_clauses', 10, 2 );
