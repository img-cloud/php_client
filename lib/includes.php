<?php
$GLOBALS['response_codes'] = array(
	"200" => "Ok",
	"201" => "Created",
	"400" => "Authorization Error",
	"401" => "Invalid request ",
	"500" => "Internal server error"
);

$GLOBALS['dimensions_codes'] = array(
	"height" => "h_",
	"width" => "w_",
	"blur" => "blur_",
	"border-color" => "-",
	"border-size" => "bdr_",
	"gray-scale" => "grayscale",
	"sepia" => "sepia",
	"scale" => "scale",
	"crop" => "crop",
);

$GLOBALS['filter_codes'] = array(
	"gray-scale" => "grayscale",
	"sepia" => "sepia",
	"scale" => "scale",
	"crop" => "crop",
);

$GLOBALS['border_colors'] = array(
	"violet",
	"indigo",
	"blue",
	"green",
	"yellow",
	"orange",
	"red"
);

$GLOBALS['image_extensions_allowed'] = array(
	'jpg',
	'jpeg',
	'png',
	'gif',
	'bmp'
);

const METHOD_GET = "GET";
const METHOD_POST = "POST";
const METHOD_DELETE = "DELETE";
