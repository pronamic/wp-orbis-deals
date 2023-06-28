<?php

global $wpdb, $post, $wp_query;

$company_id = get_post_meta( $post->ID, '_orbis_deal_company_id', true );

$company_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->orbis_companies WHERE id = %d;", $company_id ) );

if ( function_exists( 'p2p_type' ) ) {
	p2p_type( 'orbis_deals_to_companies' )->each_connected( $wp_query, array(), 'companies' );
	p2p_type( 'orbis_deals_to_persons' )->each_connected( $wp_query, array(), 'persons' );
}

$url_agreement_form = 'https://www.pronamic.nl/akkoord/';

$user  = wp_get_current_user();
$price = get_post_meta( $post->ID, '_orbis_deal_price', true );

$args = array(
	'bedrijf'                => '',
	'kvk-nummer'             => '',
	'btw-nummer'             => '',
	'voornaam'               => '',
	'achternaam'             => '',
	'straat'                 => '',
	'postcode'               => '',
	'plaats'                 => '',
	'factuur-e-mail'         => '',
	'referentie'             => sprintf( 'Deal %s', $post->ID ),
	'eenmalig'               => number_format_i18n( $price, 2 ),
	'jaarlijks'              => '0',
	'maandelijks'            => '0',
	'supplier-contact-name'  => get_the_author_meta( 'display_name' ),
	'supplier-contact-email' => get_the_author_meta( 'user_email' ),
	'orbis_deal_id'          => $post->ID,
	'orbis_deal_hash'        => wp_hash( $post->ID ),
);

$company = null;

if ( isset( $post->companies ) ) {
	$company = reset( $post->companies );
}

if ( $company ) {
	$args['bedrijf']        = get_the_title( $company );
	$args['kvk-nummer']     = get_post_meta( $company->ID, '_orbis_kvk_number', true );
	$args['btw-nummer']     = get_post_meta( $company->ID, '_orbis_vat_number', true );
	$args['straat']         = get_post_meta( $company->ID, '_orbis_address', true );
	$args['postcode']       = get_post_meta( $company->ID, '_orbis_postcode', true );
	$args['plaats']         = get_post_meta( $company->ID, '_orbis_city', true );
	$args['factuur-e-mail'] = get_post_meta( $company->ID, '_orbis_invoice_email', true );

	$update_datetime = DateTimeImmutable::createFromFormat( 'Y-m-d', '2019-11-01' );
	$post_datetime = get_post_datetime( $company );

	if ( $post_datetime < $update_datetime ) {
		$args['av-update'] = '1';
	}
}

$person = null;

if ( isset( $post->persons ) ) {
	$person = reset( $post->persons );
}

if ( $person ) {
	$names = explode( ' ', get_the_title( $person ) );

	$first_name = array_shift( $names );

	$args['voornaam'] = $first_name;

	if ( ! empty( $names ) ) {
		$args['achternaam'] = implode( ' ', $names );
	}
}

$url_agreement_form = add_query_arg( $args, $url_agreement_form );

?>
<div class="card mb-3">
	<div class="card-header"><?php esc_html_e( 'Deal Details', 'orbis_deals' ); ?></div>

	<div class="card-body">
		<div class="content">
			<dl>
				<dt><?php esc_html_e( 'Company', 'orbis_deals' ); ?></dt>
				<dd>
					<a href="<?php echo esc_attr( get_permalink( $company_post_id ) ); ?>"><?php orbis_deal_the_company_name(); ?></a>
				</dd>

				<dt><?php esc_html_e( 'Agreement Form', 'orbis_deals' ); ?></dt>
				<dd>
					<i class="fas fa-handshake"></i> <a href="<?php echo esc_url( $url_agreement_form ); ?>"><?php esc_html_e( 'Agreement Form', 'orbis_deals' ); ?></a>
				</dd>

				<dt><?php esc_html_e( 'Price', 'orbis_deals' ); ?></dt>
				<dd>
					<?php orbis_deal_the_price(); ?>
				</dd>

				<dt><?php esc_html_e( 'Status', 'orbis_deals' ); ?></dt>
				<dd>
					<?php orbis_deal_the_status(); ?>
				</dd>
			</dl>
		</div>
	</div>
</div>

<?php if ( isset( $post->companies ) ) : ?>

	<div class="card mb-3">
		<div class="card-header"><?php esc_html_e( 'Companies', 'orbis_deals' ); ?></div>

		<ul class="list">

			<?php foreach ( $post->companies as $company ) : ?>

				<li>
					<a href="<?php echo esc_url( get_permalink( $company ) ); ?>"><?php echo esc_html( get_the_title( $company ) ); ?></a>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

<?php endif; ?>

<?php if ( isset( $post->persons ) ) : ?>

	<div class="card mb-3">
		<div class="card-header"><?php esc_html_e( 'Persons', 'orbis_deals' ); ?></div>

		<ul class="list">

			<?php foreach ( $post->persons as $person ) : ?>

				<li>
					<a href="<?php echo esc_url( get_permalink( $person ) ); ?>"><?php echo esc_html( get_the_title( $person ) ); ?></a>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

<?php endif; ?>
