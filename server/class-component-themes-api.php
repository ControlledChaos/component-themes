<?php
class Component_Themes_Api {
	private static $server;
	private static $state = array( 'api' => array() );

	public static function get_api() {
		return self::$state;
	}

	public static function set_api( $api ) {
		self::$state = $api;
	}

	public static function api_data_wrapper( $component, $map_api_to_props ) {
		if ( ! is_callable( $map_api_to_props ) && WP_DEBUG ) {
			// TODO: log error
		}

		return new Component_Themes_Api_Wrapper( $component, $map_api_to_props );
	}

	public static function get_api_endpoint( $endpoint ) {
		if ( ! isset( self::$state['api'][ $endpoint ] ) ) {
			self::$state['api'][ $endpoint ] = self::fetch_required_api_endpoint( $endpoint );
		}
		return self::$state['api'][ $endpoint ];
	}

	public static function fetch_required_api_endpoint( $endpoint ) {
		if ( ! isset( self::$server ) ) {
			self::$server = rest_get_server();
		}
		$request = new WP_REST_Request( 'GET', $endpoint );
		$response = self::$server->dispatch( $request );
		if ( 200 !== $response->get_status() ) {
			return null;
		}
		$data = $response->get_data();

		return self::sanitize( $endpoint, $data );
	}

	protected static function sanitize( $endpoint, $data ) {
		if ( '/' === $endpoint ) {
			$data = ct_omit( $data, array( 'authentication', 'routes' ) );
		}

		return $data;
	}
}
