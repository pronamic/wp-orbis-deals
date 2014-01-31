<?php

class Orbis_Deal {

	/**
	 * Holds the Post object that this
	 * deal represents
	 * 
	 * @access private
	 * @var WP_Post
	 */
	private $post;

	/**
	 * Holds the PK for this deal,
	 * from the orbis_deal table
	 * 
	 * @access private
	 * @var int
	 */
	private $id;

	/**
	 * Holds the company associated id,
	 * from the orbis_deal table
	 * 
	 * @access private
	 * @var int
	 */
	private $company_id;

	/**
	 * Holds the company associated post id,
	 * from the orbis_deal table
	 *
	 * @access private
	 * @var int
	 */
	private $company_post_id;

	/**
	 * Holds the company name.
	 * 
	 * This company information could probably
	 * be split into its own class. That is outside
	 * the scope of this project for now.
	 * 
	 * @access private
	 * @var string
	 */
	private $company_name;

	/**
	 * Holds the company email
	 * 
	 * This company information could probably
	 * be split into its own class. That is outside
	 * the scope of this project for now.
	 * 
	 * @access private
	 * @var string
	 */
	private $company_email;

	/**
	 * Holds the price,
	 * from the orbis_deal table
	 * 
	 * @access private
	 * @var float
	 */
	private $price;

	/**
	 * Holds the status from the
	 * orbis_deal table
	 *
	 * @access private
	 * @var string
	 */
	private $status;

	/**
	 * Holds the associated post_id with
	 * this deal
	 *
	 * @access private
	 * @var int
	 */
	private $post_id;

	/**
	 * Constructor.
	 *
	 * @param null|WP_Post|int $deal
	 */
	public function __construct( $deal = null ) {
		if ( null !== $deal )
			$this->load( $deal );
	}

	/**
	 * Load object.
	 *
	 * @param null|WP_Post|int $deal (optional, defaults to null)
	 *
	 * @return bool $loaded
	 */
	public function load( $deal = null ) {

		// Will get global post if null set
		if ( null === $deal ) {

			global $post;
			$this->post = $post;

		// Or if a raw WP_Post object, load that
		} elseif ( $deal instanceof WP_Post ) {

			$this->post = $deal;

		// Or if just an id, find that post!
		} elseif ( is_numeric( $deal ) ) {

			$this->post = get_post( $deal );
		}

		// Check the deal from post exists
		if ( ! $this->post )
			return false;

		// Get the deal id and post type
		$post_id	 = absint( $this->post->ID );
		$post_type	 = $this->post->post_type;

		// Check this is a orbis_deal
		if ( 'orbis_deal' === $post_type ) {

			// Get all data from the custom table
			$deal_data = orbis_deal_get_data( $post_id );

			if ( ! empty( $deal_data ) ) {
				// Set the properties for this deal
				$this->set_id( $deal_data->id );
				$this->set_company_id( $deal_data->company_id );
				$this->set_company_post_id( $deal_data->company_post_id );
				$this->set_company_name( $deal_data->company_name );
				$this->set_company_email( $deal_data->company_email );
				$this->set_post_id( $deal_data->post_id );
				$this->set_price( $deal_data->price );
				$this->set_status( $deal_data->status );
			}
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Save the current state of the object. Returns false on failure and an integer value on success. The integer is
	 * not the object's ID.
	 *
	 * @return bool|int
	 */
	public function save() {

		global $wpdb;

		// Must be new
		if ( ! $this->get_id() ) {

			$data = array(
				'company_id'      => $this->get_company_id(),
				'post_id'         => $this->get_post_id(),
				'price'           => $this->get_price(),
				'status'          => $this->get_status(),
			);

			$format = array(
				'company_id'      => '%d',
				'post_id'         => '%d',
				'price'           => '%s',
				'status'          => '%s',
			);

			$result = $wpdb->insert( $wpdb->orbis_deals, $data, $format );
		} else {
			$data = array(
				'company_id'         => $this->get_company_id(),
				'price'              => $this->get_price(),
				'status'             => $this->get_status(),
			);

			$where = array( 'id' => $this->get_id() );

			$format = array(
				'company_id'         => '%d',
				'price'              => '%s',
				'status'             => '%s',
			);

			$result = $wpdb->update( $wpdb->orbis_deals, $data, $where, $format );
		}
        
		return $result;
	}

	/**
	 * ====================
	 * 
	 * SETTERS AND GETTERS
	 * 
	 * ====================
	 */
	public function get_id() {
		return $this->id;
	}

	public function set_id( $id ) {
		$this->id = $id;
		return $this;
	}

	public function get_company_id() {
		return $this->company_id;
	}

	public function set_company_id( $company_id ) {
		$this->company_id = $company_id;
		return $this;
	}

	public function get_company_post_id() {
		return $this->company_post_id;
	}

	public function set_company_post_id( $company_post_id ) {
		$this->company_post_id = $company_post_id;
		return $this;
	}

	public function get_company_name() {
		return $this->company_name;
	}

	public function set_company_name( $company_name ) {
		$this->company_name = $company_name;
		return $this;
	}

	public function get_company_email() {
		return $this->company_email;
	}

	public function set_company_email( $company_email ) {
		$this->company_email = $company_email;
		return $this;
	}

	public function get_price() {
		return $this->price;
	}

	public function set_price( $price ) {
		$this->price = $price;
		return $this;
	}

	public function get_status() {
		return $this->status;
	}

	public function set_status( $status ) {
		$this->status = $status;
		return $this;
	}

	public function get_post_id() {
		return $this->post_id;
	}

	public function set_post_id( $post_id ) {
		$this->post_id = $post_id;
		return $this;
	}
}
