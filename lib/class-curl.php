<?php

/**
 *  CurlUtils  Class
 *
 * @author Santosh Kumar Talachutla
 * @modified Santosh Kumar Talachutla
 */

include dirname( __FILE__ ) . '/class-response.php';

class CurlUtils
{
	
	private $url;
	private $authKey;
	
	public function __construct( $url, $authKey ) {
		$this->url = $url;
		$this->authKey = $authKey;
	}
	
	public function set_authKey( $authKey ) {
		$this->authKey = $authKey;
	}
	
	public function send_request( $endPoint, $params, $method, $isHeader ) {
		
		$result = new CurlResponse;
		
		$url = $this->url . $endPoint;
		error_log( "http_request(method, url)  " . $url );
		if( $isHeader )$headers[] = 'Authorization:Basic ' . $this->authKey;
		$headers[] = 'Accept: application/json';
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15' );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'User-Agent: Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15',
			'Content-Type: multipart/form-data'
		) );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
		
		// note the PUT here
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		$response = curl_exec( $ch );
		
		preg_match( '/{.*}/', $response, $matches );		
		$http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		$result->set_status_code( $http_status );
		$result->set_buff( $matches[0] );
		curl_close( $ch );
		return $result;
	}
}

