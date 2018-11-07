<?php

namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\RPC\Adapters\JsonAdapter;

class JsonAdapterMock extends JsonAdapter
{
	public function __construct()
	{
		parent::__construct('clientId', 'clientMd5');
	}

	/**
	 * @param string $method
	 * @param array $params
	 * @return array
	 */
	protected function getContent(string $method, array $params): array
	{
		$content[] = file_get_contents(__DIR__ . '/api-data/json/' .$method.'.json', false);

		return $content;
	}
}