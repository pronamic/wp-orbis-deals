<?php
/**
 * REST deals controller
 */

/**
 * REST deals controller ckass
 */
class Orbis_Deals_RestController {
	/**
	 * REST API initialize.
	 *
	 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
	 * @return void
	 */
	public function rest_api_init() {
		$namespace = 'orbis/v1';

		\register_rest_route(
			$namespace,
			'deals/(?P<post_id>[\d]+)',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'rest_api_get' ],
				'permission_callback' => function() {
					return true;
				},
				'args'                => [
					'post_id' => [
						'description' => \__( 'Deal post ID.', 'orbis-deals' ),
						'type'        => 'integer',
					],
				]
			]
		);

		\register_rest_route(
			$namespace,
			'deals/(?P<post_id>[\d]+)',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'rest_api_update' ],
				'permission_callback' => function() {
					return true;
				},
				'args'                => [
					'post_id' => [
						'description' => \__( 'Deal post ID.', 'orbis-deals' ),
						'type'        => 'integer',
					],
					'lines'   => [
						'description' => \__( 'Contains the deal lines.', 'orbis-deals' ),
						'type'        => 'array',
						'items'       => [
							'type'       => 'object',
							'properties' => [
								'quantity'    => [
									'description' => \__( 'Quantity.', 'orbis-deals' ),
									'type'        => 'integer',
									'required'    => false,
								],
								'description' => [
									'description' => \__( 'Description.', 'orbis-deals' ),
									'type'        => 'string',
									'required'    => false,
								],
								'amount' => [
									'description' => \__( 'Amount.', 'orbis-deals' ),
									'type'        => 'number',
									'required'    => false,
								],
								'recurrence' => [
									'description' => \__( 'Recurrence.', 'pronamic-twinfield' ),
									'type'        => 'string',
									'enum'        => [
										'none',
										'annual',
										'monthly',
									],
									'required'    => false,
								],
							]
						],
					]
				]
			]
		);
	}

	/**
	 * REST API get.
	 * 
	 * @param WP_REST_Request $request WordPress REST API request object.
	 * @return WP_REST_Response
	 */
	public function rest_api_get( WP_REST_Request $request ) {
		$post_id = $request->get_param( 'post_id' );

		$customer = [
			'first_name'    => '',
			'last_name'     => '',
			'company_name'  => '',
			'kvk_number'    => '',
			'vat_number'    => '',
			'address'       => '',
			'postal_code'   => '',
			'city'          => '',
			'invoice_email' => '',
		];

		// Companies.
		$companies = \get_posts(
			[
				'post_type'        => 'orbis_company',
				'connected_type'   => 'orbis_deals_to_companies',
				'connected_items'  => $post_id,
				'posts_per_page'   => 1,
				'suppress_filters' => false,
			]
		);

		$company = \reset( $companies );

		// Persons.
		$persons = \get_posts(
			[
				'post_type'        => 'orbis_person',
				'connected_type'   => 'orbis_deals_to_persons',
				'connected_items'  => $post_id,
				'posts_per_page'   => 1,
				'suppress_filters' => false,
			]
		);

		$person = \reset( $persons );

		// Data.
		if ( false !== $company ) {
			$customer['company_name']  = get_the_title( $company );
			$customer['kvk_number']    = get_post_meta( $company->ID, '_orbis_kvk_number', true );
			$customer['vat_number']    = get_post_meta( $company->ID, '_orbis_vat_number', true );
			$customer['address']       = get_post_meta( $company->ID, '_orbis_address', true );
			$customer['postal_code']   = get_post_meta( $company->ID, '_orbis_postcode', true );
			$customer['city']          = get_post_meta( $company->ID, '_orbis_city', true );
			$customer['invoice_email'] = get_post_meta( $company->ID, '_orbis_invoice_email', true );
		}

		if ( false !== $person ) {
			$names = explode( ' ', get_the_title( $person ) );

			$first_name = array_shift( $names );

			$customer['first_name'] = $first_name;
			$customer['last_name']  = implode( ' ', $names );
		}

		$lines = (array) json_decode( get_post_meta( $post_id, '_orbis_deal_lines', true ) );

		return [
			'post_id'   => $post_id,
			'reference' => \sprintf( 'Deal %s', $post_id ),
			'hash'      => \wp_hash( $post_id ),
			'customer'  => $customer,
			'supplier'  => [
				'name'  => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
				'email' => get_the_author_meta( 'user_email', get_post_field( 'post_author', $post_id ) ),
			],
			'lines'     => $lines,
		];
	}

	/**
	 * REST API update.
	 * 
	 * @param WP_REST_Request $request WordPress REST API request object.
	 * @return WP_REST_Response
	 */
	public function rest_api_update( WP_REST_Request $request ) {
		$post_id = $request->get_param( 'post_id' );

		$lines = $request->get_param( 'lines' );

		$lines = array_filter(
			$lines,
			function( $line ) {
				$line = (object) $line;

				if ( 0 !== $line->quantity ) {
					return true;
				}

				if ( '' !== $line->description ) {
					return true;
				}

				if ( 0.0 !== $line->amount ) {
					return true;
				}

				return false;
			}
		);

		update_post_meta( $post_id, '_orbis_deal_lines', wp_json_encode( $lines ) );
	}
}
