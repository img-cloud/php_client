<?php

/**
 *  ImageCloud  Class
 *
 * @author Santosh Kumar Talachutla
 * @modified Santosh Kumar Talachutla
 */

require dirname( __FILE__ ) . '/class-curl.php';
require dirname( __FILE__ ) . '/includes.php';

class ImageCloud
{
	
	private $bucket_name;
	private $api_key;
	private $target = 'http://www.imgcloud.io/';
	
	public function __construct( $api_key, $bucket_name ) {
		
		$this->bucket_name = trim( $bucket_name );
		$this->api_key = trim( $api_key );
		
		if( $this->api_key == null || empty( $this->api_key ) ) {
			throw new Exception( "API Key Should not be empty", 1 );
		}
		
		if( $this->bucket_name == null || empty( $this->bucket_name ) ) {
			throw new Exception( "Bucket Name Should not be empty", 1 );
		}
	}
	
	public function transform( $image, $dimensions, $styles, $filters, $elements, $formate = null ) {
		$response = array();
		$response['url-original'] = $this->target . $this->bucket_name . "/" . $image;
		$this->validate_image_ext( $image );
		$resp = $this->validate_dimensions( $dimensions );
		$filter_resp = $this->validate_filters( $filters );
		if( $filter_resp != null ) {
			$resp = $resp . "," . $filter_resp;
		}
		$style_resp = $this->validate_styles( $styles );
		if( $style_resp != null ) {
			$resp = $resp . "," . $style_resp;
		}
		if( $formate != null && !empty( $formate ) ) {
			$this->validate_image_ext_type( $formate );
			$ext = strtolower( substr( strrchr( $image, "." ), 1 ) );
			$image = str_replace( "." . $ext, "." . $formate, $image );
		}
		$response['url'] = $this->target . $this->bucket_name . "/" . $image;
		$response['transform_url'] = $this->target . $this->bucket_name . "/" . $resp . "/" . $image;
		$response['dom_url'] = $this->create_domurl( $elements, $response['transform_url'] );
		$response['dom_transform_url'] = $this->create_domurl( $elements, $response['url'] );
		return $response;
	}
	
	public function delete( $image ) {
		$response = array();
		try {
			if( $image != null ) {
				throw new Exception( 'Image should not be empty / null ' . $image, 1 );
			}
			$url = $this->bucket_name . "/" . $image;
			$request = new CurlUtils( $this->target, $this->api_key );
			$response = $request->send_request( $url, $response, METHOD_DELETE, false );
			if( $response->get_status_code() != 200 && $response->get_status_code() != 201 ) {
				$response->set_buff( $response_codes[$response->get_status_code() ] );
			}
		}
		catch( Exceptione $e ) {
			throw new Exception( $e->getMessage() , 1 );
		}
		return $response;
	}
	
	public function upload( $image, $post_params ) {
		
		try {
			
			$this->validate_image_ext( $image );
			$this->validate_image_file( $image );
			$response_codes = $GLOBALS['response_codes'];
			if( $post_params == null || !is_array( $post_params ) ) {
				$post_params = array();
			}
			$post_params['apiKey'] = $this->api_key;
			if( $image ) {
				$post_params['image'] = curl_file_create( $image );
				$post_params['image']->setPostFilename( $image );
			} 
			else {
				throw new Exception( 'Image file not found in local mechine at ' . $image, 1 );
			}
			
			$request = new CurlUtils( $this->target, $this->api_key );
			$response = $request->send_request( "upload", $post_params, METHOD_POST, false );
			
			if( $response->get_status_code() != 200 && $response->get_status_code() != 201 ) {
				$response->set_buff( $response_codes[$response->get_status_code() ] );
			}
		}
		catch( Exceptione $e ) {
			throw new Exception( $e->getMessage() , 1 );
		}
		return $response;
	}
	
	private function validate_dimensions( $dimensions ) {
		
		$dimensions_codes = $GLOBALS['dimensions_codes'];
		
		$response = null;
		if( $dimensions == null || !is_array( $dimensions ) ) {
			return $response;
		}
		
		foreach( $dimensions as $key => $value ) {
			if( !array_key_exists( $key, $dimensions_codes ) ) {
				throw new Exception( $key . " Filter is not supported by the Service ", 1 );
			}
			if( !is_numeric( $value ) || $value < 0 ) {
				throw new Exception( $key . " value should have positive number", 1 );
			}
			if( $response == null ) {
				$response = $dimensions_codes[$key] . $value;
			} 
			else {
				$response = $response . "," . $response = $dimensions_codes[$key] . $value;
			}
		}
		return $response;
	}
	
	private function validate_styles( $styles ) {
		$border_colors = $GLOBALS['border_colors'];
		$resp = null;
		if( array_key_exists( "border-color", $styles ) && !array_key_exists( "border-size", $styles ) ) {
			throw new Exception( "Border-size and border-color both are mutual dependent", 1 );
		}
		if( !in_array( $styles['border-color'], $border_colors ) && !$this->is_valid_colorhex( $styles['border-color'] ) ) {
			throw new Exception( "Border-color value should be a valid color code", 1 );
		}
		if( $styles['border-size'] < 0 && is_integer( $styles['border-size'] ) ) {
			throw new Exception( "Border-size should be integer ", 1 );
		}
		$resp = 'brd_' . $styles['border-size'] . "-" . $styles['border-color'];
		return $resp;
	}
	
	private function validate_filters( $filters ) {
		$filter_codes = $GLOBALS['filter_codes'];
		$response = null;
		
		foreach( $filters as $key => $value ) {
			if( !array_key_exists( $key, $filter_codes ) ) {
				throw new Exception( $key . " Filter is not supported by the Service ", 1 );
			}
			if( $value != true )continue;
			
			if( $response == null ) {
				$response = $filter_codes[$key];
			} 
			else {
				$response = $response . "," . $response = $filter_codes[$key];
			}
		}
		return $response;
	}
	
	private function create_domurl( $elements, $url ) {
		$resp = '<img ';
		foreach( $elements as $key => $value ) {
			$resp = $resp . ' ' . $key . '="' . $value . '"';
		}
		$resp = $resp . ' src="' . $url . '">';
		return $resp;
	}
	private function is_valid_colorhex(&$colorCode ) {
		$colorCode = ltrim( $colorCode, '#' );
		if( ctype_xdigit( $colorCode ) &&( strlen( $colorCode ) == 6 || strlen( $colorCode ) == 3 ) ) {
			$colorCode = "%23" . $colorCode;
			return true;
		} 
		else {
			return false;
		}
	}
	
	private function validate_image_file( $image ) {
		$file_info = getimagesize( $image );
		if( empty( $file_info ) ) {
			throw new Exception( "File should be image", 1 );
		}
	}
	
	private function validate_image_ext( $image ) {
		$ext = strtolower( substr( strrchr( $image, "." ), 1 ) );
		$this->validate_image_ext_type( $ext );
	}
	private function validate_image_ext_type( $ext ) {
		$image_extensions_allowed = $GLOBALS['image_extensions_allowed'];
		if( !in_array( $ext, $image_extensions_allowed ) ) {
			$exts = implode( ', ', $image_extensions_allowed );
			throw new Exception( "Service Supported image extensions are " . $exts, 1 );
		}
	}
}
