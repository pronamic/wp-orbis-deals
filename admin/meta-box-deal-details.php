<?php

global $wpdb, $post;

wp_nonce_field( 'orbis_save_deal_details', 'orbis_deal_details_meta_box_nonce' );

$deal = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->orbis_deals WHERE post_id = %d;", $post->ID ) );

$deal_id = $company_id = $price = $status = '';

if ( $deal ) {
	$deal_id    = $deal->id;
	$company_id = $deal->company_id;
	$price      = $deal->price;
	$status     = $deal->status;
}

?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="orbis_deal_id"><?php _e( 'Orbis ID', 'orbis' ); ?></label>
		</th>
		<td>
			<input id="orbis_deal_id" name="_orbis_deal_id" value="<?php echo esc_attr( $deal_id ); ?>" type="text" class="regular-text" readonly="readonly" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="orbis_deal_company"><?php _e( 'Company ID', 'orbis_deals' ); ?></label>
		</th>
		<td>
			<input type="text" id="orbis_deal_company" name="_orbis_deal_company_id" value="<?php echo esc_attr( $company_id ); ?>" class="orbis-id-control orbis_company_id_field regular-text" data-text="<?php echo esc_attr( $company_id ); ?>" data-text="<?php echo esc_attr( $company_id ); ?>" placeholder="<?php _e( 'Select Company', 'orbis_deals' ); ?>" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="orbis_deal_price"><?php _e( 'Price', 'orbis_deals' ); ?></label>
		</th>
		<td>
			<input id="orbis_deal_price" name="_orbis_deal_price" value="<?php echo empty( $price ) ? '' : esc_attr( number_format( $price, 2, ',', '.' ) ); ?>" type="text" class="regular-text" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="orbis_deal_status"><?php _e( 'Status', 'orbis_deals' ); ?></label>
		</th>
		<td>
			<select id="orbis_deal_status" name="_orbis_deal_status">

				<?php foreach ( orbis_deal_get_statuses() as $status_key => $status_value ) : ?>

				<option value="<?php echo $status_key; ?>" <?php selected( $status_key, $status ); ?>><?php echo $status_value; ?></option>

				<?php endforeach ?>

			</select>
		</td>
	</tr>
</table>