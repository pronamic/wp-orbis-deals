<?php

class Orbis_Deals_Plugin extends Orbis_Plugin {

	public function __construct( $file ) {

		parent::__construct( $file );

		$this->set_name( 'orbis_deals' );
		$this->set_db_version( '1.0.0' );

		$this->plugin_include( 'includes/post.php' );
		$this->plugin_include( 'includes/shortcodes.php' );
		$this->plugin_include( 'includes/deal.php' );
		$this->plugin_include( 'includes/deal-template.php' );
		$this->plugin_include( 'includes/template.php' );

		orbis_register_table( 'orbis_deals' );
	}

	public function loaded() {

		$this->load_textdomain( 'orbis_deals', '/languages/' );
	}

	public function install() {

		orbis_install_table( 'orbis_deals', '
			id BIGINT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
			company_id BIGINT(16) UNSIGNED DEFAULT NULL,
			post_id BIGINT(20) UNSIGNED DEFAULT NULL,
			price FLOAT NULL,
			status VARCHAR(16) NOT NULL DEFAULT "pending",
			PRIMARY KEY  (id),
			UNIQUE KEY post_id (post_id),
			KEY company_id (company_id)
		' );

		parent::install();
	}
}
