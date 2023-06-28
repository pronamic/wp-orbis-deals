<?php

use Pronamic\WordPress\Money\Money;

/**
 * Return the company name.
 *
 * @return null|string $company_name
 */
function orbis_deal_get_the_company_name() {
	global $post;
	global $wpdb;

	$company_id = get_post_meta( $post->ID, '_orbis_deal_company_id', true );

	$name = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM $wpdb->orbis_companies WHERE id = %d;", $company_id ) );

	return $name;
}

/**
 * Echo the company name.
 */
function orbis_deal_the_company_name() {
	echo esc_html( orbis_deal_get_the_company_name() );
}

/**
 * Return the price.
 *
 * @return null|string $price
 */
function orbis_deal_get_the_price() {
	global $post;

	$price = get_post_meta( $post->ID, '_orbis_deal_price', true );

	return $price;
}

/**
 * Echo the price.
 */
function orbis_deal_the_price() {
	$value = orbis_deal_get_the_price();

	if ( '' === $value ) {
		return;
	}

	$price = new Money( $value, 'EUR' );

	echo $price->format_i18n();
}

/**
 * Return the status. If $get_as_key is set to true, the status will be returned as key. Otherwise the status' value is
 * returned.
 *
 * @param bool $get_as_key (optional, defaults to false)
 *
 * @return null|string $status
 */
function orbis_deal_get_the_status( $get_as_key = false ) {
	global $post;

	$status = get_post_meta( $post->ID, '_orbis_deal_status', true );

	if ( ! $get_as_key ) {
		$status = orbis_deal_get_status_label( $status );
	}

	return $status;
}

/**
 * Echo the status.
 *
 * @see orbis_deal_get_the_status( $get_as_key = false )
 *
 * @param bool $get_as_key
 */
function orbis_deal_the_status( $get_as_key = false ) {
	echo esc_html( orbis_deal_get_the_status( $get_as_key ) );
}
