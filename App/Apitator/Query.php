<?php

namespace App\Apitator;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class Query
{
	/**
	 * @var Response
	 */
	public $response;
	private $method;
	private $uri;
	/**
	 * @var array
	 */
	private $options;
	private $request;

	public function __construct($method, $uri, $options = [])
	{
		$this->client = new Client;
		$this->method = $method;
		$this->uri = $uri;
		$this->options = $options;
	}

	public function getData()
	{
		return $this->getParsedBody()['data'];
	}

	public function isValid()
	{
		if ($this->response->getStatusCode() == 200 && isset($this->getParsedBody()['success'])) {
			if ($this->getParsedBody()['success'] == true){
				return true;
			}
		}
		if ($this->response->getStatusCode() == 200 && !isset($this->getParsedBody()['success'])) {
			return true;
		}

		return false;
	}

	public function getErrors()
	{
		if (!$this->isValid()) {
			return $this->getParsedBody()['errors'];
		}
	}

	public function getParsedBody()
	{
		try {
			return \GuzzleHttp\json_decode($this->response->getBody(), 1);
		}catch (\InvalidArgumentException $exception){
			return [];
		}
	}

	public function execute(Capsule $capsule)
	{
		$options = array_merge($capsule->params, $this->options, [
			'http_errors' => false
		]);
		$this->response = $this->client->request($this->method, $capsule->scheme . $capsule->endpoint . $this->uri, $options);
	}
}