<?php

class Orbis_Deals_Plugin extends Orbis_Plugin {
	public function __construct( $file ) {
		parent::__construct( $file );

		$this->set_name( 'orbis_deals' );
		$this->set_db_version( '1.0.0' );

		$this->plugin_include( 'includes/post.php' );
		$this->plugin_include( 'includes/deal-template.php' );
		$this->plugin_include( 'includes/template.php' );

		// Actions
		add_action( 'p2p_init', array( $this, 'p2p_init' ) );
	}

	public function loaded() {
		$this->load_textdomain( 'orbis_deals', '/languages/' );
	}

	/**
	 * Posts to posts initialize
	 */
	public function p2p_init() {
		p2p_register_connection_type(
			array(
				'name'        => 'orbis_deals_to_companies',
				'from'        => 'orbis_deal',
				'to'          => 'orbis_company',
				'sortable'    => 'to',
				'title'       => array(
					'from' => __( 'Companies', 'orbis-deals' ),
					'to'   => __( 'Deals', 'orbis-deals' ),
				),
				'from_labels' => array(
					'singular_name' => __( 'Deal', 'orbis-deals' ),
					'search_items'  => __( 'Search deal', 'orbis-deals' ),
					'not_found'     => __( 'No deals found.', 'orbis-deals' ),
					'create'        => __( 'Add Deal', 'orbis-deals' ),
					'new_item'      => __( 'New Deal', 'orbis-deals' ),
					'add_new_item'  => __( 'Add New Deal', 'orbis-deals' ),
				),
				'to_labels'   => array(
					'singular_name' => __( 'Company', 'orbis-deals' ),
					'search_items'  => __( 'Search company', 'orbis-deals' ),
					'not_found'     => __( 'No companies found.', 'orbis-deals' ),
					'create'        => __( 'Add Company', 'orbis-deals' ),
					'new_item'      => __( 'New Company', 'orbis-deals' ),
					'add_new_item'  => __( 'Add New Company', 'orbis-deals' ),
				),
			) 
		);

		p2p_register_connection_type(
			array(
				'name'        => 'orbis_deals_to_persons',
				'from'        => 'orbis_deal',
				'to'          => 'orbis_person',
				'sortable'    => 'to',
				'title'       => array(
					'from' => __( 'Persons', 'orbis-deals' ),
					'to'   => __( 'Deals', 'orbis-deals' ),
				),
				'from_labels' => array(
					'singular_name' => __( 'Deal', 'orbis-deals' ),
					'search_items'  => __( 'Search deal', 'orbis-deals' ),
					'not_found'     => __( 'No deals found.', 'orbis-deals' ),
					'create'        => __( 'Add Deal', 'orbis-deals' ),
					'new_item'      => __( 'New Deal', 'orbis-deals' ),
					'add_new_item'  => __( 'Add New Deal', 'orbis-deals' ),
				),
				'to_labels'   => array(
					'singular_name' => __( 'Person', 'orbis-deals' ),
					'search_items'  => __( 'Search person', 'orbis-deals' ),
					'not_found'     => __( 'No persons found.', 'orbis-deals' ),
					'create'        => __( 'Add Person', 'orbis-deals' ),
					'new_item'      => __( 'New Person', 'orbis-deals' ),
					'add_new_item'  => __( 'Add New Person', 'orbis-deals' ),
				),
			) 
		);
	}
}
