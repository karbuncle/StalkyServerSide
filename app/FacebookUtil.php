<?php

namespace App;

use GuzzleHttp\Client;

class FacebookUtil {
	const GRAPH_API_URI = 'https://graph.facebook.com/v2.5';
	const VERSION = '2.5';
	
	private static $instance;
	private $client;
	private function __construct() {
		$client = new Client();
	}
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	public function getDebugToken( $userId, $userToken ) {
		
		$userId = $request->input('userId');
		$userToken = $request->input('userToken');
		$appToken = $this->getAppAccessToken();
		 
		return $this->client->request( 'GET', self::GRAPH_API_URI . '/debug_token', [
			'input_token'  => $userToken,
			'access_token' => $appToken
		] );
	}
	public function getAppAccessToken() {
		return config( 'app.facebook_app_id' ) . '|' . config( 'app.facebook_app_secret' );
	}
}
?>