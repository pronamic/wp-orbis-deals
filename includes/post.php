<?php

/**
 * Create post types.
 */
function orbis_deals_create_initial_post_types() {
	register_post_type(
		'orbis_deal',
		array(
			'label'         => __( 'Deals', 'orbis_deals' ),
			'labels'        => array(
				'name'               => _x( 'Deals', 'post type general name', 'orbis_deals' ),
				'singular_name'      => _x( 'Deal', 'post type singular name', 'orbis_deals' ),
				'add_new'            => __( 'Add New', 'orbis_deals' ),
				'add_new_item'       => __( 'Add New Deal', 'orbis_deals' ),
				'edit_item'          => __( 'Edit Deal', 'orbis_deals' ),
				'new_item'           => __( 'New Deal', 'orbis_deals' ),
				'view_item'          => __( 'View Deal', 'orbis_deals' ),
				'search_items'       => __( 'Search Deals', 'orbis_deals' ),
				'not_found'          => __( 'No deals found', 'orbis_deals' ),
				'not_found_in_trash' => __( 'No deals found in Trash', 'orbis_deals' ),
				'parent_item_colon'  => __( 'Parent Deals:', 'orbis_deals' ),
				'menu_name'          => __( 'Deals', 'orbis_deals' ),
			),
			'public'        => true,
			'menu_position' => 30,
			'menu_icon'     => 'dashicons-format-aside',
			'supports'      => array( 'title', 'editor', 'author', 'comments' ),
			'has_archive'   => true,
			'rewrite'       => array(
				'slug' => _x( 'deals', 'slug', 'orbis_deals' ),
			)
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
		__( 'Deal Details', 'orbis_deals' ),
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
	if ( ! ( $post->post_type == 'orbis_deal' && current_user_can( 'edit_post', $post_id ) ) ) {
		return;
	}

	// OK
	$definition = array(
		'_orbis_deal_company_id' => FILTER_SANITIZE_STRING,
		'_orbis_deal_price'      => FILTER_SANITIZE_STRING,
		'_orbis_deal_status'     => FILTER_SANITIZE_STRING,
	);

	$data = filter_input_array( INPUT_POST, $definition );
	
	foreach ( $data as $key => $value ) {
		if ( empty( $value ) ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}

add_action( 'save_post', 'orbis_save_deal_details', 10, 2 );

/**
 * Deal edit columns.
 */
function orbis_deal_edit_columns( $columns ) {
	return array(
		'cb'                 => '<input type="checkbox" />',
		'title'              => __( 'Title', 'orbis_deals' ),
		'orbis_deal_company' => __( 'Person', 'orbis_deals' ),
		'orbis_deal_price'   => __( 'Person', 'orbis_deals' ),
		'orbis_deal_status'  => __( 'Status', 'orbis_deals' ),
		'author'             => __( 'Author', 'orbis_deals' ),
		'comments'           => __( 'Comments', 'orbis_deals' ),
		'date'               => __( 'Date', 'orbis_deals' ),
	);
}

add_filter( 'manage_orbis_deal_posts_columns' , 'orbis_deal_edit_columns' );

/**
 * Deal column.
 *
 * @param string $column
 * @param int    $post_id
 */
function orbis_deal_column( $column, $post_id ) {
	switch ( $column ) {
		case 'orbis_deal_company' :

			break;
		case 'orbis_deal_price' :
			// echo orbis_price( $deal->get_price() );

			break;

		case 'orbis_deal_status' :
			// $statuses = orbis_deal_get_statuses();
			// echo $statuses[ $deal->get_status() ];

			break;
	}
}

add_action( 'manage_orbis_deal_posts_custom_column' , 'orbis_deal_column', 10, 2 );
