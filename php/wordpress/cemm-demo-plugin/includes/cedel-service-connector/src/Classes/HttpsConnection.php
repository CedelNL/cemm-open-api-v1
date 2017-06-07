<?php 

namespace CedelServiceConnector\Classes;

use \CedelServiceConnector as CSC;

require_once 'HttpConnection.php';

/**
 * HttpsConnection
 * 
 * The HttpsConnection class connects to a HTTPS service. This class
 * extends the functionality of the HttpConnection.
 * 
 * @since 1.0
 * 
 * @package CedelServiceConnector
 * 
 * @author Tom Klabbers <tomklabbers@cedel.nl>
 */
class HttpsConnection extends CSC\Classes\HttpConnection {

	protected $scheme = "https://";

	protected $port = 443;


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

		/**
		 * 
		 * @todo Add SSL specific cUrl options
		 */ 

		return $curl;
	}
}