<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC;

use Igloonet\MailkitApi\RPC\Adapters\JsonAdapter;
use Igloonet\MailkitApi\RPC\Adapters\XmlAdapter;
use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;

class Client
{
	/** @var XmlAdapter  */
	private $xmlAdapter = null;

	/** @var JsonAdapter  */
	private $jsonAdapter = null;


	public function __construct(string $clientId, string $clientMd5)
	{
		$this->xmlAdapter = new XmlAdapter($clientId, $clientMd5);
		$this->jsonAdapter = new JsonAdapter($clientId, $clientMd5);
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
