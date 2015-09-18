<?php
include dirname( __FILE__ ) . '/settings.php';
include dirname( __FILE__ ) . '/lib/ImageCloud.php';

$file = dirname( __FILE__ ) . "/imgs/test.png";

$imagecloud = new ImageCloud( $GLOBALS["API_KEY"], $GLOBALS["BUCKET_NAME"] );
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

$response = $imagecloud->transform( "1442383834398_test.png", $dimentions, $styles, $filters, $elemnts , "gif");

error_log( var_dump( $response ) );
