<?php
namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\RPC\Client;
use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;

class ClientMock extends Client
{
	private JsonAdapterMock $jsonAdapter;

	public function __construct()
	{
		parent::__construct('clientId', 'clientMd5');
		$this->jsonAdapter = new JsonAdapterMock();
	}

	/**
	 * @param array<string, mixed> $params
	 * @param array<string> $possibleErrors
	 */
	public function sendRpcRequest(string $method, array $params, array $possibleErrors): IRpcResponse
	{
		return $this->jsonAdapter->sendRequest($method, $params, $possibleErrors);
	}
}
