<?php 


namespace CedelServiceConnector\Interfaces;

interface ResponseInterface {

	public function getCode();

	public function setCode($code);

	public function setBody($body);

	public function getBody();

	public function getFormat();

	public function setHeaders($headers);

	public function getHeaders();

	public function getHeader($key);

	public function hasHeader($key);

}