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

	private $user;
	private $message;

	protected function setUp()
	{
		parent::setUp();
		$this->messagesManager = new MessagesManager($this->client, ['cs'], 'cs');

		$this->user = $user= new User();
		$this->message = new Message($user);
	}

	public function testSendMail()
	{
		/** @var SendMailResult $result */
		$result = $this->messagesManager->sendMail($this->message, 12345, 1);

		Assert::same(12345678, $result->getEmailId());
		Assert::same(12345, $result->getSendingId());
		Assert::same(12345678, $result->getMessageId());
	}

	public function testMissingSendTo()
	{
		Assert::exception(function() {
			$this->messagesManager->sendMail($this->message, 54321, 1);
		}, \Igloonet\MailkitApi\Exceptions\Message\MessageSendMissingSendToException::class);
	}

	public function testInvalidIdMessages()
	{
		Assert::exception(function() {
			$this->messagesManager->sendMail($this->message, 1, 1);
		}, \Igloonet\MailkitApi\Exceptions\Message\MessageSendInvalidCampaignIdException::class);
	}

	public function testInvalidIdMailingList()
	{
		Assert::exception(function() {
			$this->messagesManager->sendMail($this->message, 2, 1);
		}, \Igloonet\MailkitApi\Exceptions\Message\MessageSendInvalidMailingListIdException::class);
	}

	public function testAttachmentNotAllowed()
	{
		Assert::exception(function() {
			$this->messagesManager->sendMail($this->message, 3, 1);
		}, \Igloonet\MailkitApi\Exceptions\Message\MessageSendAttachmentNotAllowedException::class);
	}
}

(new MessagesManagerTest)->run();
