<?php

namespace App;

use GuzzleHttp\Client;

class FacebookUtil {
	const GRAPH_API_URI = 'https://graph.facebook.com/v2.5';
	const VERSION = '2.5';
	
	private static $instance;
	private $client;
	private function __construct() {
		$this->client = new Client();
	}
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	public function getDebugToken( $userToken ) {
		return $this->graphRequest( 'debug_token', [
			'input_token'  => $userToken
		] );
	}
	public function pushAppAccessToken( $parameters ) {
		$parameters[ 'access_token' ] = config( 'app.facebook_app_id' ) . '|' . config( 'app.facebook_app_secret' );
		return $parameters;
	}
	public function graphRequest( $uri = '', $parameters = [] ) {
		return $this->client->get( 
			$method, self::GRAPH_API_URI.'/'.$uri, 
			$this->pushAppAccessToken( $parameters )
		);
	}
}
?>