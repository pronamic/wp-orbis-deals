<?php
/**
 * Deals stats
 *
 * @author    Pronamic
 * @copyright 2005-2026 Pronamic
 * @license   GPL-2.0-or-later
 * @package   Pronamic\Orbis\Deals
 */

use Pronamic\WordPress\Money\Money;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$date_query = [
	[
		'column' => 'post_date_gmt',
		'after'  => '1 year ago',
	],
];

$date = \filter_input( INPUT_GET, 'date', FILTER_UNSAFE_RAW );
$date = \is_string( $date ) ? \sanitize_text_field( \wp_unslash( $date ) ) : '';

$date_parts = \explode( '-', $date );

if ( 3 === \count( $date_parts ) ) {
	$date_query = [
		[
			'after'     => [
				'year'  => $date_parts[0],
				'month' => $date_parts[1],
				'day'   => $date_parts[2],
			],
			'inclusive' => true,
		],
	];
}

$get_deal_ids = static function ( string $status ) use ( $date_query ): array {
	$query = new WP_Query(
		[
			'post_type'              => 'orbis_deal',
			'fields'                 => 'ids',
			'nopaging'               => true, // phpcs:ignore WordPressVIPMinimum.Performance.NoPaging.nopaging_nopaging
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'date_query'             => $date_query,
			'meta_query'             => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key'   => '_orbis_deal_status',
					'value' => $status,
				],
			],
		]
	);

	return \array_map( 'intval', $query->posts );
};

$pending_deal_ids = $get_deal_ids( 'pending' );

$pending_deals = \count( $pending_deal_ids );
$won_deals     = \count( $get_deal_ids( 'won' ) );
$lost_deals    = \count( $get_deal_ids( 'lost' ) );

$total_deals = $pending_deals + $won_deals + $lost_deals;

$percentage = ( $total_deals > 0 ) ? \round( $won_deals / $total_deals * 100 ) : 0;

\update_postmeta_cache( $pending_deal_ids );

$total_amount = 0.0;

foreach ( $pending_deal_ids as $pending_deal_id ) {
	$deal_price = \get_post_meta( $pending_deal_id, '_orbis_deal_price', true );

	if ( \is_numeric( $deal_price ) ) {
		$total_amount += (float) $deal_price;
	}
}

$total_amount = new Money( $total_amount, 'EUR' );

?>
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<h1>
					<?php

					echo \esc_html( \number_format_i18n( $percentage ) . '%' );

					?>
					<span style="font-size: 16px; font-weight: normal;"><?php \esc_html_e( 'of the deals have been won', 'orbis-deals' ); ?></span>
				</h1>

				<div class="progress" role="progressbar" aria-valuenow="<?php echo \esc_attr( (string) $percentage ); ?>" aria-valuemin="0" aria-valuemax="100">
					<div class="progress-bar progress-bar-striped progress-bar-animated" style="width: <?php echo \esc_attr( (string) $percentage ); ?>%;"></div>
				</div>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-md-3">
				<p><?php \esc_html_e( 'Won deals', 'orbis-deals' ); ?></p>
				<h1><?php echo \esc_html( \number_format_i18n( $won_deals ) ); ?></h1>
			</div>

			<div class="col-md-3">
				<p><?php \esc_html_e( 'Lost deals', 'orbis-deals' ); ?></p>
				<h1><?php echo \esc_html( \number_format_i18n( $lost_deals ) ); ?></h1>
			</div>

			<div class="col-md-3">
				<p><?php \esc_html_e( 'Pending deals', 'orbis-deals' ); ?></p>
				<h1><?php echo \esc_html( \number_format_i18n( $pending_deals ) ); ?></h1>
			</div>

			<div class="col-md-3">
				<p><?php \esc_html_e( 'Total amount open', 'orbis-deals' ); ?></p>
				<h1><?php echo \esc_html( $total_amount->format_i18n() ); ?></h1>
			</div>
		</div>
	</div>
</div>
