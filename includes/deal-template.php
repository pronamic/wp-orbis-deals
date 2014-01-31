<?php

/**
 * Return the company name.
 *
 * @return null|string $company_name
 */
function orbis_deal_get_the_company_name() {
	global $post;

	$company_name = null;

	if ( isset( $post->company_name ) ) {
		$company_name = $post->company_name;
	}

	return $company_name;
}

/**
 * Echo the company name.
 */
function orbis_deal_the_company_name() {
	echo esc_attr( orbis_deal_get_the_company_name() );
}

/**
 * Return the price.
 *
 * @return null|string $price
 */
function orbis_deal_get_the_price() {
	global $post;

	$price = null;

	if ( isset( $post->deal_price ) ) {
		$price = $post->deal_price;
	}

	return $price;
}

/**
 * Echo the price.
 */
function orbis_deal_the_price() {
	echo orbis_price( orbis_deal_get_the_price() );
}

/**
 * Return the status. If $get_as_text is set to true, the status will be returned as true. Otherwise the status ID is
 * returned.
 *
 * @param bool $get_as_text (optional, defaults to true)
 *
 * @return null|int|string $status
 */
function orbis_deal_get_the_status( $get_as_text = true ) {
	global $post;

	$status = null;

	if ( isset( $post->deal_status ) ) {
		$status = $post->deal_status;

		if ( $get_as_text ) {
			$status = orbis_deal_get_statuses()[ $status ];
		}
	}

	return $status;
}

/**
 * Echo the status.
 *
 * @see orbis_deal_get_the_status( $get_as_text = true )
 */
function orbis_deal_the_status( $get_as_text = true ) {
	echo esc_attr( orbis_deal_get_the_status( $get_as_text ) );
}