<?php

namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\DataObjects\Message;
use Igloonet\MailkitApi\DataObjects\User;
use Igloonet\MailkitApi\Managers\MessagesManager;
use Igloonet\MailkitApi\Results\SendMailResult;
use Nette\Utils\Json;
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

	public function testSendMailOmitsEmptyRecipientContactAndCustomSections()
	{
		$this->messagesManager->sendMail($this->message, 12345, 1);

		$params = $this->client->getJsonAdapter()->lastParams;
		Assert::type('array', $params);
		Assert::false(array_key_exists('recipient', $params));
		Assert::false(array_key_exists('contact', $params));
		Assert::false(array_key_exists('custom', $params));

		$json = Json::encode($params);
		Assert::notContains('"recipient":[]', $json);
		Assert::notContains('"contact":[]', $json);
		Assert::notContains('"custom":[]', $json);
	}

	public function testSendMailEncodesPopulatedSectionsAsJsonObjects()
	{
		$user = new User('to@example.cz');
		$user->setStreet('Ulice');
		$user->setCustomField(1, 'foo');
		$message = new Message($user);
		$this->messagesManager->sendMail($message, 12345, 1);

		$params = $this->client->getJsonAdapter()->lastParams;
		Assert::type('array', $params);
		Assert::true(array_key_exists('contact', $params));
		Assert::true(array_key_exists('custom', $params));

		$json = Json::encode($params);
		Assert::contains('"contact":{', $json);
		Assert::contains('"custom":{', $json);
	}
}

(new MessagesManagerTest)->run();
