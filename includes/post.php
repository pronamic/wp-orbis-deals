<?php

/**
 * Create post types.
 */
function orbis_deals_create_initial_post_types() {
	register_post_type(
		'orbis_deal',
		array(
			'label'         => __( 'Deals', 'orbis-deals' ),
			'labels'        => array(
				'name'               => _x( 'Deals', 'post type general name', 'orbis-deals' ),
				'singular_name'      => _x( 'Deal', 'post type singular name', 'orbis-deals' ),
				'add_new'            => __( 'Add New', 'orbis-deals' ),
				'add_new_item'       => __( 'Add New Deal', 'orbis-deals' ),
				'edit_item'          => __( 'Edit Deal', 'orbis-deals' ),
				'new_item'           => __( 'New Deal', 'orbis-deals' ),
				'view_item'          => __( 'View Deal', 'orbis-deals' ),
				'search_items'       => __( 'Search Deals', 'orbis-deals' ),
				'not_found'          => __( 'No deals found', 'orbis-deals' ),
				'not_found_in_trash' => __( 'No deals found in Trash', 'orbis-deals' ),
				'parent_item_colon'  => __( 'Parent Deals:', 'orbis-deals' ),
				'menu_name'          => __( 'Deals', 'orbis-deals' ),
			),
			'public'        => true,
			'menu_position' => 30,
			'menu_icon'     => 'dashicons-format-aside',
			'supports'      => array( 'title', 'editor', 'author', 'comments' ),
			'has_archive'   => true,
			'rewrite'       => array(
				'slug' => _x( 'deals', 'slug', 'orbis-deals' ),
			),
		)
	);
}

add_action( 'init', 'orbis_deals_create_initial_post_types', 0 ); // highest priority

/**
 * Add meta details meta box.
 */
function orbis_deals_add_meta_boxes() {
	add_meta_box(
		'orbis_deal_details',
		__( 'Deal Details', 'orbis-deals' ),
		'orbis_deal_details_meta_box',
		'orbis_deal',
		'normal',
		'high'
	);
}

add_action( 'add_meta_boxes', 'orbis_deals_add_meta_boxes' );

/**
 * Deal details meta box
*/
function orbis_deal_details_meta_box() {
	global $orbis_deals_plugin;

	$orbis_deals_plugin->plugin_include( 'admin/meta-box-deal-details.php' );
}

/**
 * Save deal details
 *
 * @param int     $post_id
 * @param WP_Post $post
 */
function orbis_save_deal_details( $post_id, $post ) {
	// Doing autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Verify nonce
	$nonce = filter_input( INPUT_POST, 'orbis_deal_details_meta_box_nonce', FILTER_SANITIZE_STRING );
	if ( ! wp_verify_nonce( $nonce, 'orbis_save_deal_details' ) ) {
		return;
	}

	// Check permissions
	if ( ! ( 'orbis_deal' === $post->post_type && current_user_can( 'edit_post', $post_id ) ) ) {
		return;
	}

	// OK
	$definition = array(
		'_orbis_deal_company_id' => FILTER_SANITIZE_STRING,
		'_orbis_deal_price'      => array(
			'filter'  => FILTER_VALIDATE_FLOAT,
			'flags'   => FILTER_FLAG_ALLOW_THOUSAND,
			'options' => array( 'decimal' => ',' ),
		),
		'_orbis_deal_status'     => FILTER_SANITIZE_STRING,
	);

	$data = filter_input_array( INPUT_POST, $definition );

	// Status
	$status_old = get_post_meta( $post_id, '_orbis_deal_status', true );
	$status_new = $data['_orbis_deal_status'];

	foreach ( $data as $key => $value ) {
		if ( empty( $value ) ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}

	// REST
	$request = new WP_REST_Request( 'POST', '/orbis/v1/deals/' . $post_id );

	$lines = [];

	if ( array_key_exists( 'orbis_deal_lines', $_POST ) ) {
		$lines = array_map(
			function ( $line ) {
				$quantity    = 0;
				$description = '';
				$link        = '';
				$amount      = 0;
				$recurrence  = 'none';

				if ( array_key_exists( 'quantity', $line ) ) {
					$quantity = (int) sanitize_text_field( $line['quantity'] );
				}

				if ( array_key_exists( 'description', $line ) ) {
					$description = sanitize_text_field( $line['description'] );
				}

				if ( array_key_exists( 'link', $line ) ) {
					$link = sanitize_url( $line['link'] );
				}

				if ( array_key_exists( 'amount', $line ) ) {
					$value  = sanitize_text_field( $line['amount'] );
					$amount = ( '' !== $value ) ? $value : $amount;
				}

				if ( array_key_exists( 'recurrence', $line ) ) {
					$value      = sanitize_text_field( $line['recurrence'] );
					$recurrence = ( '' !== $value ) ? $value : $recurrence;
				}

				return (object) [
					'quantity'    => $quantity,
					'description' => $description,
					'link'        => $link,
					'amount'      => $amount,
					'recurrence'  => $recurrence,
				];
			},
			wp_unslash( $_POST['orbis_deal_lines'] )
		);
	}

	$request->set_param( 'lines', $lines );

	$response = \rest_do_request( $request );

	// Action
	if ( 'publish' === $post->post_status && $status_old !== $status_new ) {
		// @see https://github.com/woothemes/woocommerce/blob/v2.1.4/includes/class-wc-order.php#L1274
		do_action( 'orbis_deal_status_' . $status_old . '_to_' . $status_new, $post_id );
		do_action( 'orbis_deal_status_update', $post_id, $status_old, $status_new );
	}
}

add_action( 'save_post', 'orbis_save_deal_details', 10, 2 );

/**
 * Deal status update
 *
 * @param int $post_id
 */
function orbis_deal_status_update( $post_id, $status_old, $status_new ) {
	$user = wp_get_current_user();

	$comment_type = 'orbis_comment';
	switch ( $status_new ) {
		case 'won':
			$comment_type = 'orbis_deal_won';
			break;
		case 'lost':
			$comment_type = 'orbis_deal_lost';
			break;
	}

	$comment_content = sprintf(
		/* translators: title of post, status(won, lost), name of user */
		__( "The deal '%1\$s' was marked '%2\$s' by %3\$s.", 'orbis-deals' ),
		get_the_title( $post_id ),
		orbis_deal_get_status_label( $status_new ),
		$user->display_name
	);

	$data = array(
		'comment_post_ID' => $post_id,
		'comment_content' => $comment_content,
		'comment_author'  => 'Orbis',
		'comment_type'    => $comment_type,
	);

	$comment_id = wp_insert_comment( $data );
}

add_action( 'orbis_deal_status_update', 'orbis_deal_status_update', 10, 3 );

/**
 * Deal edit columns.
 */
function orbis_deal_edit_columns( $columns ) {
	return array(
		'cb'                 => '<input type="checkbox" />',
		'title'              => __( 'Title', 'orbis-deals' ),
		'orbis_deal_company' => __( 'Company', 'orbis-deals' ),
		'orbis_deal_price'   => __( 'Price', 'orbis-deals' ),
		'orbis_deal_status'  => __( 'Status', 'orbis-deals' ),
		'author'             => __( 'Author', 'orbis-deals' ),
		'comments'           => __( 'Comments', 'orbis-deals' ),
		'date'               => __( 'Date', 'orbis-deals' ),
	);
}

add_filter( 'manage_orbis_deal_posts_columns', 'orbis_deal_edit_columns' );

/**
 * Deal column.
 *
 * @param string $column
 * @param int    $post_id
 */
function orbis_deal_column( $column, $post_id ) {
	switch ( $column ) {
		case 'orbis_deal_company':
			orbis_deal_the_company_name();

			break;
		case 'orbis_deal_price':
			orbis_deal_the_price();

			break;

		case 'orbis_deal_status':
			orbis_deal_the_status();

			break;
	}
}

add_action( 'manage_orbis_deal_posts_custom_column', 'orbis_deal_column', 10, 2 );

/**
 * Defaults
 *
 * @param unknown $query
 */
function orbis_deals_pre_get_posts( $query ) {
	$post_type = $query->get( 'post_type' );

	if ( 'orbis_deal' === $post_type ) {
		// Status
		$status = $query->get( 'orbis_deal_status' );

		if ( $status ) {
			$meta_query = $query->get( 'meta_query' );

			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}

			$meta_query[] = array(
				'key'   => '_orbis_deal_status',
				'value' => $status,
			);

			$query->set( 'meta_query', $meta_query );
		}
	}
}

add_action( 'pre_get_posts', 'orbis_deals_pre_get_posts' );

function orbis_deals_query_vars( $query_vars ) {
	$query_vars[] = 'orbis_deal_status';

	return $query_vars;
}

add_filter( 'query_vars', 'orbis_deals_query_vars' );


add_filter(
	'the_content',
	function ( $content ) {
		if ( 'orbis_deal' !== get_post_type() ) {
			return $content;
		}

		$post_id = get_the_ID();

		$orbis_deal = (object) [
			'post_id' => $post_id,
			'lines'   => (array) json_decode( get_post_meta( $post_id, '_orbis_deal_lines', true ) ),
		];

		ob_start();

		include __DIR__ . '/../templates/deal-lines.php';

		$add = ob_get_clean();

		return $content . $add;
	}
);
