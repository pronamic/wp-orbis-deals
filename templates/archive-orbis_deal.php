<?php
/**
 * Archive deals
 *
 * @author    Pronamic
 * @copyright 2005-2026 Pronamic
 * @license   GPL-2.0-or-later
 * @package   Pronamic\Orbis\Deals
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\get_header();

include __DIR__ . '/deals-stats.php';

?>
<hr />

<div class="card">
	<?php \get_template_part( 'templates/search_form' ); ?>

	<?php if ( \have_posts() ) : ?>

		<div class="table-responsive">
			<table class="table table-striped table-condense table-hover">
				<thead>
					<tr>
						<th><?php \esc_html_e( 'Date', 'orbis-deals' ); ?></th>
						<th><?php \esc_html_e( 'Organization', 'orbis-deals' ); ?></th>
						<th><?php \esc_html_e( 'Title', 'orbis-deals' ); ?></th>
						<th><?php \esc_html_e( 'Price', 'orbis-deals' ); ?></th>
						<th><?php \esc_html_e( 'Status', 'orbis-deals' ); ?></th>
						<th><?php \esc_html_e( 'Author', 'orbis-deals' ); ?></th>
						<th><span class="visually-hidden"><?php \esc_html_e( 'Actions', 'orbis-deals' ); ?></span></th>
					</tr>
				</thead>
				<tbody>
					<?php

					while ( \have_posts() ) :
						\the_post();

						?>

						<tr id="post-<?php \the_ID(); ?>" <?php \post_class(); ?>>
							<td>
								<?php echo \esc_html( (string) \get_the_date() ); ?>
							</td>
							<td>
								<?php \orbis_deal_the_organization_name(); ?>
							</td>
							<td>
								<a href="<?php \the_permalink(); ?>"><?php \the_title(); ?></a>

								<?php \get_template_part( 'templates/table-cell-comments' ); ?>
							</td>
							<td>
								<?php \orbis_deal_the_price(); ?>
							</td>
							<td>
								<?php \orbis_deal_the_status(); ?>
							</td>
							<td>
								<?php \the_author(); ?>
							</td>
							<td>
								<?php \get_template_part( 'templates/table-cell-actions' ); ?>
							</td>
						</tr>

					<?php endwhile; ?>
				</tbody>
			</table>
		</div>

	<?php else : ?>

		<div class="card-body">
			<?php \get_template_part( 'templates/content-none' ); ?>
		</div>

	<?php endif; ?>
</div>

<?php

if ( \function_exists( 'orbis_content_nav' ) ) {
	\orbis_content_nav();
} else {
	\the_posts_pagination();
}

\get_footer();
