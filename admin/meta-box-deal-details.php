<?php

global $wpdb, $post;

wp_nonce_field( 'orbis_save_deal_details', 'orbis_deal_details_meta_box_nonce' );

$company_id = get_post_meta( $post->ID, '_orbis_deal_company_id', true );
$price      = get_post_meta( $post->ID, '_orbis_deal_price', true );
$status     = get_post_meta( $post->ID, '_orbis_deal_status', true );

$company = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM $wpdb->orbis_companies WHERE id=%s", $company_id ) );

?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="orbis_deal_company"><?php esc_html_e( 'Company ID', 'orbis_deals' ); ?></label>
		</th>
		<td>
			<select id="orbis_deal_company" name="_orbis_deal_company_id" class="orbis-id-control orbis_company_id_field regular-text">
				<option id="orbis_select2_default" value="<?php echo esc_attr( $company_id ); ?>">
					<?php echo esc_attr( $company ); ?>
				</option>
			</select>
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

<?php

$lines = (array) json_decode( get_post_meta( $post->ID, '_orbis_deal_lines', true ) );

$new_line = (object) [
	'quantity'    => '',
	'description' => '',
	'amount'      => '',
	'period'      => '',
];

$lines[] = $new_line;
$lines[] = $new_line;
$lines[] = $new_line;
$lines[] = $new_line;
$lines[] = $new_line;

?>
<table>
	<thead>
		<tr>
			<th scope="col"><?php esc_html_e( 'Quantity', 'orbis_deals' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Description', 'orbis_deals' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Amount', 'orbis_deals' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Recurrence', 'orbis_deals' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php foreach ( $lines as $key => $line ) : ?>

			<tr>
				<td>
					<input type="number" name="orbis_deal_lines[<?php echo esc_attr( $key ) ; ?>][quantity]" value="<?php echo esc_attr( $line->quantity ); ?>">
				</td>
				<td>
					<input type="text" name="orbis_deal_lines[<?php echo esc_attr( $key ) ; ?>][description]" value="<?php echo esc_attr( $line->description ); ?>">
				</td>
				<td>
					<input type="number" name="orbis_deal_lines[<?php echo esc_attr( $key ) ; ?>][amount]" min="0" step="0.01" value="<?php echo esc_attr( $line->amount ); ?>">
				</td>
				<td>
					<?php

					$options = [
						'none'   => __( 'None', 'orbis_deals' ),
						'annual' => __( 'Annual', 'orbis_deals' ),
					];

					?>
					<select name="orbis_deal_lines[<?php echo esc_attr( $key ) ; ?>][recurrence]">
						<?php

						foreach ( $options as $value => $label ) {
							printf(
								'<option value="%s" %s>%s</option>',
								esc_attr( $value ),
								selected( $value, $line->recurrence, false ),
								esc_html( $label )
							);
						}

						?>
					</select>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>
