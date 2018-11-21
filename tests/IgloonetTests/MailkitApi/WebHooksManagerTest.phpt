<?php

namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\DataObjects\Enums\UnsubscribeMethod;
use Igloonet\MailkitApi\DataObjects\SubscribeWebHook;
use Igloonet\MailkitApi\DataObjects\User;
use Igloonet\MailkitApi\Managers\WebHooksManager;
use Nette\Utils\DateTime;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class WebHooksManagerTest extends MailkitTestCase
{
	/** WebHooksManager */
	private $webHooksManager;

	protected function setUp()
	{
		$this->webHooksManager = new WebHooksManager();
	}

	public function testResponseProcessSubscribe()
	{
		$file = file_get_contents(__DIR__ . '/mock-files/api-data/json/mailkit.webhooks.subscribe.json', false);

		/** @var SubscribeWebHook $response */
		$response = $this->webHooksManager->processSubscribe($file);

		Assert::same('1', $response->getEmailId());
		Assert::type(DateTime::class, $response->getDate());
		Assert::same('192.168.100.100', $response->getIp());
		Assert::same('Igloo', $response->getUser()->getFirstName());
		Assert::same('fejjo@igloonet.cz', $response->getUser()->getEmail());
		Assert::same('123456789', $response->getUser()->getPhone());

		for ($i = 1; $i <= User::CUSTOM_FIELDS_CNT; $i++) {
			Assert::same('CUSTOM'.$i, $response->getUser()->getCustomField($i));
		}

	}

	public function testResponseProcessUnsubscribe()
	{
		$file = file_get_contents(__DIR__ . '/mock-files/api-data/json/mailkit.webhooks.unsubscribe.json', false);
		/** @var UnsubscribeMethod $response */
		$response = $this->webHooksManager->processUnsubscribe($file);

		Assert::same('1', $response->getEmailId());
		Assert::type(DateTime::class, $response->getDate());
		Assert::same('192.168.100.100', $response->getIp());
		Assert::same(UnsubscribeMethod::get('api_unsubscribe'), $response->getMethod());
	}


}

(new WebHooksManagerTest)->run();


