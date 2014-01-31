<?php if ( isset( $data ) && $data instanceof $data ) : ?>

<div class="panel">
	<table class="table table-striped table-bordered">
		<thead>
		<tr>
			<th scope="col"><?php _e( 'Orbis ID', 'orbis_deals' ); ?></th>
			<th scope="col"><?php _e( 'Title'   , 'orbis_deals' ); ?></th>
			<th scope="col"><?php _e( 'Company' , 'orbis_deals' ); ?></th>
			<th scope="col"><?php _e( 'Price'   , 'orbis_deals' ); ?></th>
			<th scope="col"><?php _e( 'Status'  , 'orbis_deals' ); ?></th>
		</tr>
		</thead>

		<tbody>

		<?php foreach ( $data->results as $i => $result ) : ?>

		<tr>
			<td>
				<?php echo $result->id; ?>
			</td>
			<td>
				<?php echo $result->title; ?>
			</td>
			<td>
				<?php echo $result->company_name; ?>
			</td>
			<td>
				<?php echo orbis_price( $result->price ); ?>
			</td>
			<td>
				<?php echo $data->statuses[ $result->status ]; ?>
			</td>
		</tr>

		<?php endforeach; ?>

		</tbody>
	</table>
</div>

<?php endif; ?>