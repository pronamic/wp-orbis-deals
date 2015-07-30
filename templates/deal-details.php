<?php

global $wpdb, $post;

$subscription = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->orbis_deals WHERE post_id = %d;", $post->ID ) );

$company_id = 0;

if ( $subscription ) {
	$company_id      = $subscription->company_id;
}

$company_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->orbis_companies WHERE id = %d;", $company_id ) );

?>
<div class="panel">
	<header>
		<h3><?php esc_html_e( 'Deal Details', 'orbis_deals' ); ?></h3>
	</header>

	<div class="content">
		<dl>
			<dt><?php esc_html_e( 'Company', 'orbis_deals' ); ?></dt>
			<dd>
				<a href="<?php echo esc_attr( get_permalink( $company_post_id ) ); ?>"><?php orbis_deal_the_company_name(); ?></a>
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
