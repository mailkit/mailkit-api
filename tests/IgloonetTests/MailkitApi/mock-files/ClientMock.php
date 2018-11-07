<?php
namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\RPC\Client;
use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;

class ClientMock extends Client
{
	private $xmlAdapter = null;
	private $jsonAdapter = null;

	public function __construct()
	{
		parent::__construct('clientId', 'clientMd5');
		$this->xmlAdapter = new XmlAdapterMock();
		$this->jsonAdapter = new JsonAdapterMock();
	}

	/**
	 * @param string $method
	 * @param array $params
	 * @param array $possibleErrors
	 * @return IRpcResponse
	 */
	public function sendRpcRequest(string $method, array $params, array $possibleErrors): IRpcResponse
	{
		if ($this->jsonAdapter->supportsMethod($method)) {
//			return $this->jsonAdapter->sendRequest($method, $params, $possibleErrors);
		}

		return $this->xmlAdapter->sendRequest($method, $params, $possibleErrors);
	}
}