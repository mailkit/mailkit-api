<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Managers;

use Igloonet\MailkitApi\DataObjects\SubscribeWebHook;
use Igloonet\MailkitApi\DataObjects\UnsubscribeWebHook;
use Nette\Utils\Json;

class WebHooksManager
{
	public function processSubscribe(string $content): SubscribeWebHook
	{
		$responseData = Json::decode($content, Json::FORCE_ARRAY);
		return SubscribeWebHook::fromArray($responseData);
	}

	public function processUnsubscribe(string $content): UnsubscribeWebHook
	{
		$responseData = Json::decode($content, Json::FORCE_ARRAY);
		return UnsubscribeWebHook::fromArray($responseData);
	}
}