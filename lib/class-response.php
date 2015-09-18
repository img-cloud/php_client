<?php

/**
 *  CurlResponse  Class
 *
 * @package lib
 * @subpackage .
 * @author Santosh Kumar Talachutla
 * @modified Santosh Kumar Talachutla
 */

class CurlResponse
{
	private $status_code;
	private $buff;
	function __construct() {
	}
	
	public function set_status_code( $status_code ) {
		$this->status_code = intval($status_code);
	}
	public function get_status_code() {
		return intval($this->status_code);
	}
	
	public function set_buff( $buff ) {
		$this->buff = $buff;
	}
	
	public function get_buff() {
		return $this->buff;
	}
}
?>
