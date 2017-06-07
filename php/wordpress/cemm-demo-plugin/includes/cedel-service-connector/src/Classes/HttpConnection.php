<?php 

namespace CedelServiceConnector\Classes;

use \CedelServiceConnector as CSC;

require_once __DIR__ . '/../Exceptions/InvalidHostException.php';
require_once __DIR__ . '/../Interfaces/ConnectionInterface.php';

/**
 * HttpConnection
 * 
 * The HttpConnection class connects to a HTTP service;
 * 
 * @since 1.0
 * 
 * @package CedelServiceConnector
 * 
 * @author Tom Klabbers <tomklabbers@cedel.nl>
 */
class HttpConnection implements CSC\Interfaces\ConnectionInterface {

	protected $scheme = "http://";

	protected $port = 80;

	protected $hostname;


	/**
	 * setCurlOptions
	 * 
	 * This function is used to add curl options before a request is send.
	 * 
	 * @since 1.0
	 * 
	 * @param $hostname 	String 		The hostname of the service to connect with including a scheme e.g. http://127.0.0.1 or http://localhost
	 * 
	 * @return Object 		The curl handle needs to be returned.
	 */ 
	protected function setCurlOptions($curl) {

		return $curl;
	}

	/**
	 * setHost
	 * 
	 * Sets the hostname for the connection. This function accepts all urls. 
	 * It parses the url to get the hostname. An exception is thrown if the 
	 * given hostname is invalid.
	 * 
	 * @since 1.0
	 * 
	 * @param $hostname 	String 		The hostname of the service to connect with including a scheme e.g. http://127.0.0.1 or http://localhost
	 * 
	 * @throws \CedelServiceConnector\Exceptions\InvalidHostException
	 */ 
	public function setHost($hostname) {
		$host = parse_url($hostname, PHP_URL_HOST);

		if(! empty($host)) {
			$this->hostname = $host;
		}
		else {
			throw new CSC\Exceptions\InvalidHostException();
		}
	}


	/**
	 * getHost
	 * 
	 * Returns the hostname.
	 * 
	 * @since 1.0
	 * 
	 * @return String
	 */ 
	public function getHost(){
		return $this->hostname;
	}


	/**
	 * setPort
	 * 
	 * Sets the port to connect to. The default port is 80
	 * 
	 * @since 1.0
	 * 
	 * @param $port 	Integer 	The port number
	 * 
	 * @return Boolean
	 */ 
	public function setPort($port) {
		$port = intval($port);

		if($port > 0){
			$this->port = $port;
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 * getPort
	 * 
	 * Returns the port number.
	 * 
	 * @since 1.0
	 * 
	 * @return Integer
	 */ 
	public function getPort() {
		return $this->port;
	}


	/**
	 * getUrl
	 * 
	 * Return the complete url constructed from the connection
	 * parameters.
	 * 
	 * @since 1.0
	 * 
	 * @return String
	 */
	public function getUrl() {
		$url = $this->scheme . $this->hostname;
		
		if(! ($this->port == 80 || $this->port == 443)) {
			$url .= ':' . $this->port;
		}

		return $url . '/';
	}
}