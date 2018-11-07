<?php

namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\DataObjects\Message;
use Igloonet\MailkitApi\DataObjects\User;
use Igloonet\MailkitApi\Managers\MessagesManager;
use Igloonet\MailkitApi\Results\SendMailResult;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class MessagesManagerTest extends MailkitTestCase
{
	/** MessagesManager $messagesManager*/
	private $messagesManager;

	protected function setUp()
	{
		parent::setUp();
		$this->messagesManager = new MessagesManager($this->client, ['cs'], 'cs');
	}
	public function testOne()
	{
		$user = new User();
		$message = new Message($user);

		/** @var SendMailResult $result */
		$result = $this->messagesManager->sendMail($message, 12345, 1);

		Assert::same(12345678, $result->getEmailId());
		Assert::same(12345, $result->getSendingId());
		Assert::same(12345678, $result->getMessageId());
//		Assert::same(0, $result->getStatus());
	}

}

(new MessagesManagerTest)->run();
