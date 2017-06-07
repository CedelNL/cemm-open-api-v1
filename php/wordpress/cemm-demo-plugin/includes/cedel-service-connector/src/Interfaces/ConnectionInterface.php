<?php 


namespace CedelServiceConnector\Interfaces;

interface ConnectionInterface {
	
	public function setHost($hostname);

	public function getHost();

	public function setPort($port);

	public function getPort();

	public function getUrl();
}