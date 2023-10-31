<?php
/**
 * Deal lines
 * 
 * @package orbis-deals
 */

use Pronamic\WordPress\Money\Money;

if ( null === $orbis_deal || 0 === count( $orbis_deal->lines ) ) {
	return;
}

$total = new Money();

?>
<table class="table table-striped table-sm">
	<thead>
		<tr>
			<th scope="col"><?php \esc_html_e( 'Description', 'orbis-deals' ); ?></th>
			<th scope="col"><?php \esc_html_e( 'Quantity', 'orbis-deals' ); ?></th>
			<th scope="col"><?php \esc_html_e( 'Amount', 'orbis-deals' ); ?></th>
			<th scope="col"><?php \esc_html_e( 'Total', 'orbis-deals' ); ?></th>
			<th scope="col"><?php \esc_html_e( 'Recurrence', 'orbis-deals' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<?php foreach ( $orbis_deal->lines as $line ) : ?>

			<tr>
				<?php

				$line_amount = new Money( $line->amount );
				$line_total  = $line_amount->multiply( $line->quantity );
				$total       = $total->add( $line_total );

				?>
				<td>
					<?php 

					if ( '' === (string) $line->link ) {
						echo \esc_html( $line->description );
					}

					if ( '' !== (string) $line->link ) {
						\printf(
							'<a href="%s">%s</a>',
							\esc_url( $line->link ),
							\esc_html( $line->description )
						);
					}

					?>
				</td>
				<td>
					<?php echo \esc_html( number_format_i18n( $line->quantity ) ); ?> ×
				</td>
				<td>
					<?php echo \esc_html( $line_amount->format_i18n() ); ?>
				</td>
				<td>
					<?php echo \esc_html( $line_total->format_i18n() ); ?>
				</td>
				<td>
					<?php

					switch ( $line->recurrence ) {
						case 'annual':
							\esc_html_e( 'Annual', 'orbis-deals' );

							break;
						case 'monthly':
							\esc_html_e( 'Monthly', 'orbis-deals' );

							break;
						case 'none':
							\esc_html_e( 'One-time', 'orbis-deals' );

							break;
						default:
							echo \esc_html( $link->recurrence );

							break;
					}

					?>
				</td>
			</tr>

		<?php endforeach; ?>

	</tbody>

	<tfoot>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<?php echo \esc_html( $total->format_i18n() ); ?>
			</td>
			<td></td>
		</tr>
	</tfoot>
</table>
