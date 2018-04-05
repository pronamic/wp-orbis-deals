<?php

global $wpdb, $post;

$company_id = get_post_meta( $post->ID, '_orbis_deal_company_id', true );

$company_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->orbis_companies WHERE id = %d;", $company_id ) );

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
