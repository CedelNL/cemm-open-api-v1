<?php 

namespace CedelServiceConnector\Classes;

use \CedelServiceConnector as CSC;

require_once 'HttpRequest.php';

class GetRequest extends CSC\Classes\HttpRequest {

	protected function setCurlOptions() {
		curl_setopt($this->curl, CURLOPT_HTTPGET, true);
	}
	
}