<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC;

use Igloonet\MailkitApi\RPC\Adapters\JsonAdapter;
use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;

class Client
{
	private JsonAdapter $jsonAdapter;

	public function __construct(string $clientId, string $clientMd5)
	{
		$this->jsonAdapter = new JsonAdapter($clientId, $clientMd5);
	}

	public function sendRpcRequest(string $method, array $params, array $possibleErrors): IRpcResponse
	{
		return $this->jsonAdapter->sendRequest($method, $params, $possibleErrors);
	}
}
