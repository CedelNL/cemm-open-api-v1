<?php

/**
 * CedelServiceConnector
 * 
 * Include this file in your source code to use the CedelServiceConnector (CSC).
 * The CSC can be used to connect to a service providec by Cedel. We do not 
 * support services that are not developed by Cedel. The CSC is build to be used
 * for HTTP(S) connections, but we have not implemented the full HTTP specification.
 * 
 * (c) Cedel B.V. <info@cedel.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @package CedelServiceConnector
 * 
 * 
 * @author 	Tom Klabbers <tomklabbers@cedel.nl>
 * 
 * @version 1.2.2
 * 
 * 
 */

/*
	Example usage:
 
 	use \CedelServiceConnector\Classes\GetRequest;
 	use \CedelServiceConnector\Classes\HttpsConnection;

 	$conn = new HttpsConnection();
	$conn->setHost("https://mijn.cemm.nl");

	$req = new GetRequest($conn);
	$req->setPath('open-api/v1/cemm/');
	$req->setParam('api-key', "<api-key>");

	$req->send();

	if($req->hasResponse()){
		$res = $req->getResponse();

		$body = $res->getBody();
	}
*/

include_once 'src/Classes/HttpConnection.php';

include_once 'src/Classes/HttpsConnection.php';

include_once 'src/Classes/GetRequest.php';

include_once 'src/Classes/PostRequest.php';

include_once 'src/Classes/HttpResponse.php';