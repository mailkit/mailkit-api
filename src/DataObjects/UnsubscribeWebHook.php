<?php

namespace Igloonet\MailkitApi\DataObjects;

use Igloonet\MailkitApi\DataObjects\Enums\UnsubscribeMethod;
use Nette\Utils\DateTime;

class UnsubscribeWebHook
{
	/** @var User|null */
	private $user = null;

	/** @var string|null */
	private $emailId = null;

	/** @var DateTime|null */
	private $date = null;

	/** @var string|null */
	private $ip = null;

	/** @var string|null */
	private $ipOrig = null;

	/** @var string|null */
	private $mailingListId = null;

	/** @var string|null */
	private $sendId = null;

	/** @var string|null */
	private $messageId = null;

	/** @var string|null */
	private $topicActiveId = null;

	/** @var string|null */
	private $topicInactiveId = null;

	/** @var string|null */
	private $timeout = null;

	/** @var string|null */
	private $expire = null;

	/** @var UnsubscribeMethod|null */
	private $method = null;

	/** @var string|null */
	private $unsubscribeAnswer = null;

	/** @var string|null */
	private $unsubscribeNote = null;

	/** $jsonContent */
	private $jsonContent = null;

	private function __construct(array $jsonContent, User $user) {
		$this->jsonContent = $jsonContent;
		$this->user = $user;
	}

	public static function fromArray($jsonContent)
	{
		//validation

		$user = self::createUser($jsonContent);
		$subscribe =  new static($jsonContent, $user);

		$subscribe->user = $user;
		$subscribe->emailId = self::validateEmptyString($jsonContent['ID_EMAIL']);
		$subscribe->date = new DateTime($jsonContent['DATE']);
		$subscribe->ip = self::validateIp($jsonContent['IP']);
		$subscribe->ipOrig = self::validateIp($jsonContent['IP_ORIG']);
		$subscribe->mailingListId = self::validateEmptyString($jsonContent['ID_ML']);
		$subscribe->sendId = self::validateEmptyString($jsonContent['ID_SEND']);
		$subscribe->messageId = self::validateEmptyString($jsonContent['ID_MESSAGE']);
		$subscribe->topicActiveId = self::validateEmptyString($jsonContent['ID_TOPIC_ACTIVE']);
		$subscribe->topicInactiveId = self::validateEmptyString($jsonContent['ID_TOPIC_INACTIVE']);
		$subscribe->timeout = self::validateEmptyString($jsonContent['TIMEOUT']);
		$subscribe->expire = new DateTime($jsonContent['EXPIRE']);
		$subscribe->method = UnsubscribeMethod::get($jsonContent['METHOD']);
		$subscribe->unsubscribeAnswer = self::validateEmptyString($jsonContent['UNSUBSCRIBE_ANSWER']);
		$subscribe->unsubscribeNote = self::validateEmptyString($jsonContent['UNSUBSCRIBE_NOTE']);

		return $subscribe;
	}

	/**
	 * @return User|null
	 */
	public function getUser(): ?User
	{
		return $this->user;
	}

	/**
	 * @return null|string
	 */
	public function getEmailId(): ?string
	{
		return $this->emailId;
	}

	/**
	 * @return DateTime|null
	 */
	public function getDate(): ?DateTime
	{
		return $this->date;
	}

	/**
	 * @return null|string
	 */
	public function getIp(): ?string
	{
		return $this->ip;
	}

	/**
	 * @return null|string
	 */
	public function getIpOrig(): ?string
	{
		return $this->ipOrig;
	}

	/**
	 * @return null|string
	 */
	public function getMailingListId(): ?string
	{
		return $this->mailingListId;
	}

	/**
	 * @return null|string
	 */
	public function getSendId(): ?string
	{
		return $this->sendId;
	}

	/**
	 * @return null|string
	 */
	public function getMessageId(): ?string
	{
		return $this->messageId;
	}

	/**
	 * @return null|string
	 */
	public function getTopicActiveId(): ?string
	{
		return $this->topicActiveId;
	}

	/**
	 * @return null|string
	 */
	public function getTopicInactiveId(): ?string
	{
		return $this->topicInactiveId;
	}

	/**
	 * @return null|string
	 */
	public function getTimeout(): ?string
	{
		return $this->timeout;
	}

	/**
	 * @return null|string
	 */
	public function getExpire(): ?string
	{
		return $this->expire;
	}

	/**
	 * @return UnsubscribeMethod|null
	 */
	public function getMethod(): ?UnsubscribeMethod
	{
		return $this->method;
	}

	/**
	 * @return null|string
	 */
	public function getUnsubscribeAnswer(): ?string
	{
		return $this->unsubscribeAnswer;
	}

	/**
	 * @return null|string
	 */
	public function getUnsubscribeNote(): ?string
	{
		return $this->unsubscribeNote;
	}

	private static function validateEmptyString($string)
	{
		return trim($string ?? '') === '' ? null : trim($string);
	}

	private static function validateIp($ipAddress)
	{
		if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
			return $ipAddress;
		}

		return null;
	}

	/**
	 * @param array $jsonContent
	 * @return User
	 */
	private static function createUser(array $jsonContent): User
	{
		$user = new User($jsonContent['EMAIL']);

		return $user;
	}
}