<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects;

use Igloonet\MailkitApi\DataObjects\Enums\UnsubscribeMethod;

class UnsubscribeWebHook
{
	/** @var User|null */
	private $user = null;

	/** @var string|null */
	private $emailId = null;

	/** @var \DateTimeImmutable|null */
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

	/** @var \DateTimeImmutable|null */
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
		$user = self::createUser($jsonContent);
		$subscribe = new static($jsonContent, $user);

		$subscribe->user = $user;
		$subscribe->emailId = self::validateEmptyString($jsonContent['ID_EMAIL']);
		$subscribe->date = new \DateTimeImmutable($jsonContent['DATE']);
		$subscribe->ip = self::validateIp($jsonContent['IP']);
		$subscribe->ipOrig = self::validateIp($jsonContent['IP_ORIG']);
		$subscribe->mailingListId = self::validateEmptyString($jsonContent['ID_ML']);
		$subscribe->sendId = self::validateEmptyString($jsonContent['ID_SEND']);
		$subscribe->messageId = self::validateEmptyString($jsonContent['ID_MESSAGE']);
		$subscribe->topicActiveId = self::validateEmptyString($jsonContent['ID_TOPIC_ACTIVE']);
		$subscribe->topicInactiveId = self::validateEmptyString($jsonContent['ID_TOPIC_INACTIVE']);
		$subscribe->timeout = self::validateEmptyString($jsonContent['TIMEOUT']);
		$subscribe->expire = new \DateTimeImmutable($jsonContent['EXPIRE']);
		$subscribe->method = UnsubscribeMethod::from($jsonContent['METHOD']);
		$subscribe->unsubscribeAnswer = self::validateEmptyString($jsonContent['UNSUBSCRIBE_ANSWER']);
		$subscribe->unsubscribeNote = self::validateEmptyString($jsonContent['UNSUBSCRIBE_NOTE']);

		return $subscribe;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function getEmailId(): ?string
	{
		return $this->emailId;
	}

	public function getDate(): ?\DateTimeImmutable
	{
		return $this->date;
	}

	public function getIp(): ?string
	{
		return $this->ip;
	}

	public function getIpOrig(): ?string
	{
		return $this->ipOrig;
	}

	public function getMailingListId(): ?string
	{
		return $this->mailingListId;
	}

	public function getSendId(): ?string
	{
		return $this->sendId;
	}

	public function getMessageId(): ?string
	{
		return $this->messageId;
	}

	public function getTopicActiveId(): ?string
	{
		return $this->topicActiveId;
	}

	public function getTopicInactiveId(): ?string
	{
		return $this->topicInactiveId;
	}

	public function getTimeout(): ?string
	{
		return $this->timeout;
	}

	public function getExpire(): ?\DateTimeImmutable
	{
		return $this->expire;
	}

	public function getMethod(): ?UnsubscribeMethod
	{
		return $this->method;
	}

	public function getUnsubscribeAnswer(): ?string
	{
		return $this->unsubscribeAnswer;
	}

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

	private static function createUser(array $jsonContent): User
	{
		$user = new User($jsonContent['EMAIL']);

		return $user;
	}
}
