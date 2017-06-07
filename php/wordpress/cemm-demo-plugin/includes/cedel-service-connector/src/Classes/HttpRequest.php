<?php 

namespace CedelServiceConnector\Classes;

use \CedelServiceConnector as CSC;

require_once __DIR__ . '/../Interfaces/RequestInterface.php';
require_once __DIR__ . '/../Classes/HttpResponse.php';

class HttpRequest implements CSC\Interfaces\RequestInterface {

	protected $connection;

	protected $path;

	protected $params;

	protected $headers = array();

	protected $curl;

	protected $raw_response;

	protected $parsed_response;

	protected $curl_error = 0;

	protected $response_code = 0;

	protected $trailing_slash = true;

	private $response_header_size = 0;

	protected $response;

	public function __construct(CSC\Interfaces\ConnectionInterface $connection, $options = array()) {
		$this->connection = $connection;

		if(isset($options['auto_trailing_slash'])){
			$this->trailing_slash = $options['auto_trailing_slash'];
		}
	}

	public function disableTrailingSlash() {
		$this->trailing_slash = false;
	}
	public function enableTrailingSlash() {
		$this->trailing_slash = true;
	}

	public function setPath($path) {
		// Strip the forward slashes from the beginning 
		// and end of the string if auto_trailing_slash is enabled 
		if($this->trailing_slash) {
			$path = trim($path, '/'); 
		}
		else {
			$path = ltrim($path, '/'); 
		}

		$this->path = $path;
	}

	public function getPath() {
		return $this->path;
	}

	public function setParam($key, $value) {
		$this->params[$key] = $value;
	}

	public function getParam($key) {
		if(isset($this->params[$key])) {
			return $this->params[$key];
		}

		return false;
	}

	public function unsetParam($key) {
		if(isset($this->params[$key])) {
			unset($this->params[$key]);
		}

		return false;
	}

	public function setParams($params) {

		foreach ($params as $key => $value) {
			$this->setParams($key, $value);
		}

	}

	public function getParams() {
		$params = array();

		foreach ($this->params as $key => $value) {
			$params[$key] = $value;
		}

		return $params;
	}


	/**
	 * setHeader
	 * 
	 * This function does not validate the given headers
	 */
	public function setHeader($header, $value) {
		$this->headers[$header] = $value;
	}

	public function getHeader($header) {
		if(isset($this->headers[$header])) {
			return $this->headers[$header];
		}

		return false;
	}

	public function getUrl(){
		$url = $this->connection->getUrl();

		if($this->path) {
			$url .= $this->path;
		}

		if($this->trailing_slash){
			// Trim the trailing slash first before adding it,
			// to prevent a double slash at the end. 
			$url = rtrim($url, '/');
			$url .= '/';
		}

		if(! empty($this->params)){
			$url .= '?' . http_build_query($this->params);
		}

		return $url;
	}

	public function send() {
		$this->curl = curl_init(); 

        // set url 
        curl_setopt($this->curl, CURLOPT_URL, $this->getUrl());
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->curl, CURLOPT_VERBOSE, 0);
		curl_setopt($this->curl, CURLOPT_HEADER, 1);

        //return the transfer as a string 
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); 

       

        if(is_callable(array($this, 'setCurlOptions'))) {
        	$this->setCurlOptions();
        }



        if(is_callable(array($this->connection, 'setCurlOptions'))) {
        	$this->curl = $this->connection->setCurlOptions($this->curl);
        }

 
        $this->raw_response 		= curl_exec($this->curl);
        $this->response_code 			= curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->curl_error 			= curl_errno($this->curl);
        $this->response_header_size	= curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);

        // close curl resource to free up system resources 
        curl_close($this->curl);

        $this->curl = null;

        if($this->curl_error || $this->response_code != 200) {
        	return $this->handleCurlError();
        }
        else {
        	return $this->parseResponse();
        }
     
	}

	protected function parseResponse() {
		$this->response = HttpResponse::parse($this->raw_response, $this->response_code);

		return true;
	}

	protected function handleCurlError() {
		/**
		 * 
		 * @todo Make implementation to handle request errors.
		 */
		switch ($this->response_code) {
            case 403:
                // Request responded with a 403 Unauthorized
            case 412:
                // Request responded with a 412 Precondition Failed
                // This status code is returned if an invalid alias is used.
            case 408:
                // Request responded with a 408 Request timeout
            case 502:
                // Request responded with a 502 Bad Gateway.
                // The api was not able to request data from the given CEMM
            default:
             
          }

          return false;
	}

	protected function setCurlOptions() {}

	public function getResponse() {
		return $this->response;
	}

	public function hasResponse() {
		return (is_subclass_of($this->response, '\CedelServiceConnector\Interfaces\ResponseInterface'));
	}
	

	public function hasError() {
		if( $this->response_code != 200 ){
			return true;
		}

		return false;
	}

	public function getError() {
		return $this->response_code;
	}
}