<?php

global $wpdb, $post, $wp_query;

$organization_id = get_post_meta( $post->ID, '_orbis_deal_organization_id', true );

$organization_post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM %i WHERE id = %d;', $wpdb->prefix . 'orbis_organizations', $organization_id ) );

if ( function_exists( 'p2p_type' ) ) {
	p2p_type( 'orbis_deals_to_organizations' )->each_connected( $wp_query, array(), 'organizations' );
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
				<dt><?php esc_html_e( 'Organization', 'orbis-deals' ); ?></dt>
				<dd>
					<a href="<?php echo \esc_url( get_permalink( $organization_post_id ) ); ?>"><?php orbis_deal_the_organization_name(); ?></a>
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

<?php if ( isset( $post->organizations ) ) : ?>

	<div class="card mb-3">
		<div class="card-header"><?php esc_html_e( 'Organizations', 'orbis-deals' ); ?></div>

		<ul class="list">

			<?php foreach ( $post->organizations as $organization ) : ?>

				<li>
					<a href="<?php echo esc_url( get_permalink( $organization ) ); ?>"><?php echo esc_html( get_the_title( $organization ) ); ?></a>
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
