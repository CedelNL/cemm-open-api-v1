<?php

namespace CedelServiceConnector\Classes;

use \CedelServiceConnector as CSC;

require_once 'HttpRequest.php';

class PostRequest extends CSC\Classes\HttpRequest {

	private $postfields = array();

	public function setPostData($data) {
		$this->postfields = $data;
	}

	protected function setCurlOptions() {
		curl_setopt($this->curl, CURLOPT_POST, true);

		/**
		 * 
		 * The PHP documentation states:
		 * 
		 * The full data to post in a HTTP "POST" operation. To post a file, prepend a filename with @ and use the full path. The filetype can be explicitly specified by following the filename 
		 * with the type in the format ';type=mimetype'. This parameter can either be passed as a urlencoded string like 'para1=val1&para2=val2&...' or as an array with the field name as key 
		 * and field data as value. If value is an array, the Content-Type header will be set to multipart/form-data. As of PHP 5.2.0, value must be an array if files are passed to this option 
		 * with the @ prefix. As of PHP 5.5.0, the @ prefix is deprecated and files can be sent using CURLFile. The @ prefix can be disabled for safe passing of values beginning with @ by 
		 * setting the CURLOPT_SAFE_UPLOAD option to TRUE.
		 */
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->postfields);
	}
	
}