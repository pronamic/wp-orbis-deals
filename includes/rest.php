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

		$lines = (array) json_decode( get_post_meta( $post_id, '_orbis_deal_lines', true ) );

		return [
			'post_id' => $post_id,
			'lines'   => $lines,
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
