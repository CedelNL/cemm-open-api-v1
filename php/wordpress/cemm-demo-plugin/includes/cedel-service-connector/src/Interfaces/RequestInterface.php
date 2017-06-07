<?php 


namespace CedelServiceConnector\Interfaces;

interface RequestInterface {

	public function __construct(\CedelServiceConnector\Interfaces\ConnectionInterface $connection, $options = array());

	public function setPath($path);

	public function getPath();

	public function setParam($key, $value);

	public function unsetParam($key);

	public function getParam($key);

	public function setParams($params);

	public function getParams();

	public function setHeader($header, $value);

	public function getHeader($header);

	public function getUrl();

	public function send();

	public function getResponse();

	public function hasResponse();

	public function hasError();

	public function getError();
	
}