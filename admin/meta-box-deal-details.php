<?php

global $wpdb, $post;

wp_nonce_field( 'orbis_save_deal_details', 'orbis_deal_details_meta_box_nonce' );

$company_id = get_post_meta( $post->ID, '_orbis_deal_company_id', true );
$price      = get_post_meta( $post->ID, '_orbis_deal_price', true );
$status     = get_post_meta( $post->ID, '_orbis_deal_status', true );

?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="orbis_deal_company"><?php esc_html_e( 'Company ID', 'orbis_deals' ); ?></label>
		</th>
		<td>
			<select type="text" id="orbis_deal_company" name="_orbis_deal_company_id" value="<?php echo esc_attr( $company_id ); ?>" class="orbis-id-control orbis_company_id_field regular-text" data-text="<?php echo esc_attr( $company_id ); ?>" data-text="<?php echo esc_attr( $company_id ); ?>" placeholder="<?php esc_attr_e( 'Select Company', 'orbis_deals' ); ?>"></select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="orbis_deal_price"><?php esc_html_e( 'Price', 'orbis_deals' ); ?></label>
		</th>
		<td>
			<input id="orbis_deal_price" name="_orbis_deal_price" value="<?php echo empty( $price ) ? '' : esc_attr( number_format( $price, 2, ',', '.' ) ); ?>" type="text" class="regular-text" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="orbis_deal_status"><?php esc_html_e( 'Status', 'orbis_deals' ); ?></label>
		</th>
		<td>
			<select id="orbis_deal_status" name="_orbis_deal_status">
				<?php

				foreach ( orbis_deal_get_statuses() as $status_key => $status_value ) {
					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $status_key ),
						selected( $status_key, $status, false ),
						esc_html( $status_value )
					);
				}

				?>
			</select>
		</td>
	</tr>
</table>
