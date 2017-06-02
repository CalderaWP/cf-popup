<?php

/**
 * Created by PhpStorm.
 * User: christie
 * Date: 5/31/17
 * Time: 2:59 PM
 */
class CF_Popup_API {
	/**
	 * Adds routes
	 *
	 * @since 0.0.3
	 */
	public function add_routes () {
		register_rest_route ( Caldera_Forms_API_Util::api_namespace(), 'cf-popup/settings',
			array (
				'methods' => 'POST',
				'callback' => array( $this, 'update_settings' ),
				'args' => array (
					'forms' => array (
						'type' => 'object',
						'required' => 'true'
					)
				),
				'permissions_callback' => array ( $this, 'permissions' )
			)
		);
		register_rest_route ( Caldera_Forms_API_Util::api_namespace(), 'cf-popup/settings',
			array (
				'methods' => 'GET',
				'callback' => array( $this, 'get_settings' ),
				'args' => array (

				),
				'permissions_callback' => array( $this, 'permissions' )
			)
		);
	}
	/**
	 * Check request permissions
	 *
	 * @since 0.0.3
	 *
	 * @return bool
	 */
	public function permissions() {
		return current_user_can( Caldera_Forms::get_manage_cap() );
	}

	/**
	 * Updates settings
	 *
	 * @since 0.0.3
	 *
	 * @param WP_REST_request $request
	 * @return WP_REST_Response
	 */
	public function update_settings ( WP_REST_Request $request ) {
		$settings = $request->get_param( 'forms' );

		CF_Popup_Settings::save_settings( $settings );

		$response = rest_ensure_response( CF_Popup_Settings::get_settings() );
		$response->set_status(201);
		return $response;

	}
	/**
	 * Get settings via API
	 *
	 * @since 0.0.3
	 *
	 * @param WP_REST_request $request
	 * @return WP_REST_Response
	 */
	public function get_setting( WP_REST_request $request ) {
		return rest_ensure_response ( CF_Popup_Settings::get_settings() );
	}

}