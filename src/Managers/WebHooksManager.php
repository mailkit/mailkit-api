<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Managers;

use Igloonet\MailkitApi\DataObjects\SubscribeWebHook;
use Igloonet\MailkitApi\DataObjects\UnsubscribeWebHook;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class WebHooksManager
{
	public function processSubscribe($content)
	{
		try {
			$responseData = Json::decode($content, Json::FORCE_ARRAY);
			$subscribe = SubscribeWebHook::fromArray($responseData);

			return $subscribe;
		} catch (JsonException $e) {
		}

		return null;
	}

	public function processUnsubscribe($content)
	{
		try {
			$responseData = Json::decode($content, Json::FORCE_ARRAY);
			$unsubscribe = UnsubscribeWebHook::fromArray($responseData);

			return $unsubscribe;
		} catch (JsonException $e) {
		}

		return null;
	}
}