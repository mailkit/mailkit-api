<?php

namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\RPC\Adapters\JsonAdapter;

class JsonAdapterMock extends JsonAdapter
{
	public function __construct()
	{
		parent::__construct('clientId', 'clientMd5');
	}

	protected function getContent(string $method, array $params): array
	{
		$requestData = ['function' => $method, 'parameters' => $params];

		foreach (['email_id', 'ID_user_list', 'mailinglist_id', 'name', 'ID_email'] as $key) {
			if (isset($params[$key])) {
				$suffix = (string)$params[$key];
				$file = __DIR__ . '/api-data/json/' . $method . '.' . $suffix . '.json';
				if (file_exists($file)) {
					return [$requestData, file_get_contents($file)];
				}
				// Key found but no matching fixture — signal failure
				return [$requestData, false];
			}
		}

		// No param key — fall back to method-only fixture
		$file = __DIR__ . '/api-data/json/' . $method . '.json';
		if (file_exists($file)) {
			return [$requestData, file_get_contents($file)];
		}

		return [$requestData, false];
	}
}
