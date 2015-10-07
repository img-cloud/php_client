<?php
$base = realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' );
require_once( join( DIRECTORY_SEPARATOR, array(
	$base,
	'lib',
	'ImangeCloud.php'
) ) );
require_once( join( DIRECTORY_SEPARATOR, array(
	$base,
	'',
	'settings.php'
) ) );

class ApiTest extends PHPUnit_Framework_TestCase
{
	
	$imagecloud = new ImageCloud( $GLOBALS["API_KEY"], $GLOBALS["BUCKET_NAME"] );
	function test() {
		
		$options = array(
			"cloud_name" => "test321"
		);
		$this->cloudinary_url_assertion( "test", $options, "http://res.cloudinary.com/test321/image/upload/test" );
		
		$response = $imagecloud->transform( "1442383834398_test.png", $dimentions, $styles, $filters, $elemnts, "gif" );
	}
	
	private function imgcloud_url_assertion( $source, $options, $expected ) {
		
		$post_params = array(
			"folder" => "myfolder",
			"tags" => "mytag"
		);
		
		$dimentions = array(
			'height' => 23,
			'width' => 34,
			'blur' => 20
		);
		$filters = array(
			'sepia' => true,
			'gray-scale' => false,
			'crop' => true,
			'scale' => true
		);
		$styles = array(
			'border-color' => 'ffffff',
			'border-size' => 20
		);
		$elemnts = array(
			'class' => 'myclass',
			'style' => 'width:37',
			'alt' => 'myalt',
			'title' => 'title'
		);
		
		$response = $imagecloud->transform( "1442383834398_test.png", $dimentions, $styles, $filters, $elemnts, "gif" );
		$this->assertEquals( array() , $options );
		$this->assertEquals( $expected, $response );
	}
}
