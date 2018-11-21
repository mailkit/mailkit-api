<?php

namespace Igloonet\MailkitApi\DataObjects;


use Igloonet\MailkitApi\DataObjects\Enums\Gender;
use Nette\Utils\DateTime;

class SubscribeWebHook
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
	private $channel = null;

	/** @var string|null */
	private $userAgentString = null;

	/** @var DateTime|null */
	private $dateRequest = null;

	/** @var string|null */
	private $userAgentRequest = null;

	/** @var string|null */
	private $ipRequest = null;

	/** @var string|null */
	private $ipOrigRequest = null;

	/** @var string|null */
	private $urlCode = null;

	/** @var array|string[] */
	private $customFields = [];

	/** $jsonContent */
	private $jsonContent = null;

	private function __construct(array $jsonContent, User $user) {
		$this->jsonContent = $jsonContent;
		$this->user = $user;
	}

	public static function fromArray($jsonContent)
	{
		$user = self::createUser($jsonContent);
		$subscribe =  new static($jsonContent, $user);

		$subscribe->user = $user;
		$subscribe->emailId = self::validateEmptyString($jsonContent['ID_EMAIL']);
		$subscribe->date = new DateTime($jsonContent['DATE']);
		$subscribe->ip = self::validateIp($jsonContent['IP']);
		$subscribe->ipOrig = self::validateIp($jsonContent['IP_ORIG']);
		$subscribe->mailingListId = self::validateEmptyString($jsonContent['ID_ML']);
		$subscribe->channel = self::validateEmptyString($jsonContent['CHANNEL']);
		$subscribe->userAgentString =self::validateEmptyString($jsonContent['UA']);
		$subscribe->dateRequest = new DateTime($jsonContent['DATE_REQUEST']);
		$subscribe->userAgentRequest = self::validateEmptyString($jsonContent['UA_REQUEST']);
		$subscribe->ipRequest = self::validateIp($jsonContent['IP_REQUEST']);
		$subscribe->ipOrigRequest = self::validateIp($jsonContent['IP_ORIG_REQUEST']);
		$subscribe->urlCode = self::validateEmptyString($jsonContent['URL_CODE']);

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
	public function getChannel(): ?string
	{
		return $this->channel;
	}

	/**
	 * @return null|string
	 */
	public function getUserAgentString(): ?string
	{
		return $this->userAgentString;
	}

	/**
	 * @return DateTime|null
	 */
	public function getDateRequest(): ?DateTime
	{
		return $this->dateRequest;
	}

	/**
	 * @return null|string
	 */
	public function getUserAgentRequest(): ?string
	{
		return $this->userAgentRequest;
	}

	/**
	 * @return null|string
	 */
	public function getIpRequest(): ?string
	{
		return $this->ipRequest;
	}

	/**
	 * @return null|string
	 */
	public function getIpOrigRequest(): ?string
	{
		return $this->ipOrigRequest;
	}

	/**
	 * @return null|string
	 */
	public function getUrlCode(): ?string
	{
		return $this->urlCode;
	}

	/**
	 * @param array $jsonContent
	 * @return User
	 */
	private static function createUser(array $jsonContent): User
	{
		$user = new User($jsonContent['EMAIL']);
		$user->setFirstName(self::validateEmptyString($jsonContent['FIRST_NAME']));
		$user->setLastName(self::validateEmptyString($jsonContent['LAST_NAME']));
		$user->setFax(self::validateEmptyString($jsonContent['FAX']));
		$user->setGender(Gender::get($jsonContent['GENDER']));
		$user->setMobile(self::validateEmptyString($jsonContent['MOBILE']));
		$user->setNickName(self::validateEmptyString($jsonContent['NICK_NAME']));
		$user->setPhone(self::validateEmptyString($jsonContent['PHONE']));
		$user->setPrefix(self::validateEmptyString($jsonContent['PREFIX']));
		$user->setReplyTo(self::validateEmptyString($jsonContent['REPLY_TO']));
		$user->setState(self::validateEmptyString($jsonContent['STATE']));
		$user->setStreet(self::validateEmptyString($jsonContent['STREET']));
		$user->setVocative(self::validateEmptyString($jsonContent['VOCATIVE']));
		$user->setZip(self::validateEmptyString($jsonContent['ZIP']));
		$user->setCity(self::validateEmptyString($jsonContent['CITY']));
		$user->setCompany(self::validateEmptyString($jsonContent['COMPANY']));
		$user->setCountry(self::validateEmptyString($jsonContent['COUNTRY']));

		for ($i = 1; $i <= User::CUSTOM_FIELDS_CNT; $i++) {
			$user->setCustomField($i, $jsonContent['CUSTOM'.$i] ?? null);
		}

		return $user;
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

}