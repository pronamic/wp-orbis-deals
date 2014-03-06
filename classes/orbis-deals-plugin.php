<?php

class Orbis_Deals_Plugin extends Orbis_Plugin {
	public function __construct( $file ) {
		parent::__construct( $file );

		$this->set_name( 'orbis_deals' );
		$this->set_db_version( '1.0.0' );

		$this->plugin_include( 'includes/post.php' );
		$this->plugin_include( 'includes/deal-template.php' );
		$this->plugin_include( 'includes/template.php' );
	}

	public function loaded() {
		$this->load_textdomain( 'orbis_deals', '/languages/' );
	}
}
