<?php

namespace CedelServiceConnector\Classes;

use \CedelServiceConnector as CSC;

require_once __DIR__ . '/../Interfaces/ResponseInterface.php';

class HttpResponse implements CSC\Interfaces\ResponseInterface {

	protected $code = 200;

	protected $body = '';

	protected $headers = '';

	protected $format = "plaintext";

	public function getCode() {
		return $this->code;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function setBody($body) {
		/**
		 * Version 1.0 only supports plaintext and JSON
		 * response body.
		 */

		// Parse JSON as assoc array
		$parsed_json = json_decode($body, true);

		if(! is_null($parsed_json)) {
			$this->body 	= $parsed_json;
			$this->format 	= 'json';
		}
		else {
			$this->body 	= $body;
			$this->format 	= 'plaintext';
		}
	}

	public function getBody() {
		return $this->body;
	}

	public function getFormat() {
		return $this->format;
	}

	public function setHeaders($headers){
		$this->headers = $headers;
	}

	public function getHeaders(){}

	public function getHeader($key){}

	public function hasHeader($key){}

	public static function parse($raw, $code){
		$response = new HttpResponse();
		
		$response->setCode($code);
		
		/**
		 * Extract header and body from raw http response
		 */
		list($header, $body) = explode("\r\n\r\n", $raw, 2);
		
		// Check if the header contains HTTP 100 Continue header. If this
		// exists the real header is still in the body.
		if(strpos($header," 100 Continue")!==false){
		    list( $header, $body) = explode( "\r\n\r\n", $body , 2);
		}

		$response->setHeaders($header);
		$response->setBody($body);

		return $response;
	}
}