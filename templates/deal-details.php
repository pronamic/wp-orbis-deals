<?php

global $wpdb, $post, $wp_query;

$company_id = get_post_meta( $post->ID, '_orbis_deal_company_id', true );

$company_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->orbis_companies WHERE id = %d;", $company_id ) );

if ( function_exists( 'p2p_type' ) ) {
	p2p_type( 'orbis_deals_to_companies' )->each_connected( $wp_query, array(), 'companies' );
	p2p_type( 'orbis_deals_to_persons' )->each_connected( $wp_query, array(), 'persons' );
}

$url_agreement_form = add_query_arg(
	[
		'orbis_deal_id'   => $post->ID,
		'orbis_deal_hash' => wp_hash( $post->ID ),
	],
	'https://www.pronamic.nl/akkoordformulier/'
);

?>
<div class="card mb-3">
	<div class="card-header"><?php esc_html_e( 'Deal Details', 'orbis-deals' ); ?></div>

	<div class="card-body">
		<div class="content">
			<dl>
				<dt><?php esc_html_e( 'Company', 'orbis-deals' ); ?></dt>
				<dd>
					<a href="<?php echo \esc_url( get_permalink( $company_post_id ) ); ?>"><?php orbis_deal_the_company_name(); ?></a>
				</dd>

				<dt><?php esc_html_e( 'Agreement Form', 'orbis-deals' ); ?></dt>
				<dd>
					<i class="fas fa-handshake"></i> <a href="<?php echo esc_url( $url_agreement_form ); ?>"><?php esc_html_e( 'Agreement Form', 'orbis-deals' ); ?></a>
				</dd>

				<dt><?php esc_html_e( 'Price', 'orbis-deals' ); ?></dt>
				<dd>
					<?php orbis_deal_the_price(); ?>
				</dd>

				<dt><?php esc_html_e( 'Status', 'orbis-deals' ); ?></dt>
				<dd>
					<?php orbis_deal_the_status(); ?>
				</dd>
			</dl>
		</div>
	</div>
</div>

<?php if ( isset( $post->companies ) ) : ?>

	<div class="card mb-3">
		<div class="card-header"><?php esc_html_e( 'Companies', 'orbis-deals' ); ?></div>

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
		<div class="card-header"><?php esc_html_e( 'Persons', 'orbis-deals' ); ?></div>

		<ul class="list">

			<?php foreach ( $post->persons as $person ) : ?>

				<li>
					<a href="<?php echo esc_url( get_permalink( $person ) ); ?>"><?php echo esc_html( get_the_title( $person ) ); ?></a>
				</li>

			<?php endforeach; ?>

		</ul>
	</div>

<?php endif; ?>
