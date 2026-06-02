<?php

namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\RPC\Adapters\JsonAdapter;

class JsonAdapterMock extends JsonAdapter
{
	public ?string $lastMethod = null;

	/** @var array<string, mixed>|null */
	public ?array $lastParams = null;

	public function __construct()
	{
		parent::__construct('clientId', 'clientMd5');
	}

	/**
	 * @param array<string, mixed> $params
	 * @return array{array<string, mixed>, string|false, int|null}
	 */
	protected function getContent(string $method, array $params): array
	{
		$this->lastMethod = $method;
		$this->lastParams = $params;

		$requestData = ['function' => $method, 'parameters' => $params];

		foreach (['ID_user_list', 'mailinglist_id', 'email', 'ID_email', 'name'] as $key) {
			if (isset($params[$key])) {
				$suffix = (string)$params[$key];
				$file = __DIR__ . '/api-data/json/' . $method . '.' . $suffix . '.json';
				if (file_exists($file)) {
					return [$requestData, file_get_contents($file), null];
				}
				// Key found but no matching fixture — signal failure
				return [$requestData, false, null];
			}
		}

		// No param key — fall back to method-only fixture
		$file = __DIR__ . '/api-data/json/' . $method . '.json';
		if (file_exists($file)) {
			return [$requestData, file_get_contents($file), null];
		}

		return [$requestData, false, null];
	}
}
