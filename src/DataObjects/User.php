<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects;

use Igloonet\MailkitApi\DataObjects\Enums\Gender;
use Igloonet\MailkitApi\DataObjects\Enums\InsertStatus;
use Igloonet\MailkitApi\DataObjects\Enums\UserStatus;
use Igloonet\MailkitApi\Exceptions\User\InvalidCustomFieldNumberException;

class User
{
	public const CUSTOM_FIELDS_CNT = 25;

	/** @var int|null */
	private $id = null;

	/** @var UserStatus|null  */
	private $status = null;

	/** @var InsertStatus|null */
	private $insertStatus = null;

	/** @var string|null  */
	private $email = null;

	/** @var string|null  */
	private $firstName = null;

	/** @var string|null  */
	private $lastName = null;

	/** @var string|null */
	private $prefix = null;

	/** @var string|null */
	private $vocative = null;

	/** @var string|null */
	private $nickName = null;

	/** @var string|null */
	private $company = null;

	/** @var Gender|null */
	private $gender = null;

	/** @var string|null */
	private $phone = null;

	/** @var string|null */
	private $mobile = null;

	/** @var string|null */
	private $fax = null;

	/** @var string|null */
	private $street = null;

	/** @var string|null */
	private $city = null;

	/** @var string|null */
	private $state = null;

	/** @var string|null  */
	private $country = null;

	/** @var string|null */
	private $zip = null;

	/** @var string|null */
	private $replyTo = null;

	/** @var int|null */
	private $mailingListId = null;

	/** @var array|string[] */
	private $customFields = [];

	public function __construct(string $email = null)
	{
		$this->email = $email;
	}

	/**
	 * @param int|null $id
	 * @return $this
	 */
	public function setId(?int $id): self
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @param UserStatus|null $status
	 * @return $this
	 */
	public function setStatus(?UserStatus $status): self
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * @return UserStatus|null
	 */
	public function getStatus(): ?UserStatus
	{
		return $this->status;
	}

	/**
	 * @param InsertStatus|null $insertStatus
	 * @return $this
	 */
	public function setInsertStatus(?InsertStatus $insertStatus): self
	{
		$this->insertStatus = $insertStatus;

		return $this;
	}

	/**
	 * @return InsertStatus|null
	 */
	public function getInsertStatus(): ?InsertStatus
	{
		return $this->insertStatus;
	}

	/**
	 * @param string|null $email
	 * @return $this
	 */
	public function setEmail(?string $email): self
	{
		$this->email = $email === null ? null : trim($email);

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

	/**
	 * @param string|null $firstName
	 * @return $this
	 */
	public function setFirstName(?string $firstName): self
	{
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	/**
	 * @param string|null $lastName
	 * @return $this
	 */
	public function setLastName(?string $lastName): self
	{
		$this->lastName = $lastName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	/**
	 * @param string|null $prefix
	 * @return $this
	 */
	public function setPrefix(?string $prefix): self
	{
		$this->prefix = $prefix;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPrefix(): ?string
	{
		return $this->prefix;
	}

	/**
	 * @param string|null $vocative
	 * @return $this
	 */
	public function setVocative(?string $vocative): self
	{
		$this->vocative = $vocative;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getVocative(): ?string
	{
		return $this->vocative;
	}

	/**
	 * @param string|null $nickName
	 * @return $this
	 */
	public function setNickName(?string $nickName): self
	{
		$this->nickName = $nickName;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getNickName(): ?string
	{
		return $this->nickName;
	}

	/**
	 * @param string|null $company
	 * @return $this
	 */
	public function setCompany(?string $company): self
	{
		$this->company = $company;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getCompany(): ?string
	{
		return $this->company;
	}

	/**
	 * @param Gender|null $gender
	 * @return $this
	 */
	public function setGender(?Gender $gender): self
	{
		$this->gender = $gender;

		return $this;
	}

	/**
	 * @return Gender|null
	 */
	public function getGender(): ?Gender
	{
		return $this->gender;
	}

	/**
	 * @param string $gender
	 * @return bool
	 */
	private function isValidGender(string $gender): bool
	{
		return in_array(trim($gender), [
			Gender::getAvailableValues(),
			'male',
			'female',
			'muz',
			'zena',
			'm',
			'f'
		], true);
	}

	/**
	 * @param string|null $phone
	 * @return $this
	 */
	public function setPhone(?string $phone): self
	{
		$this->phone = $phone;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPhone(): ?string
	{
		return $this->phone;
	}

	/**
	 * @param string|null $mobile
	 * @return $this
	 */
	public function setMobile(?string $mobile): self
	{
		$this->mobile = $mobile;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getMobile(): ?string
	{
		return $this->mobile;
	}

	/**
	 * @param string|null $fax
	 * @return $this
	 */
	public function setFax(?string $fax): self
	{
		$this->fax = $fax;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getFax(): ?string
	{
		return $this->fax;
	}

	/**
	 * @param string|null $street
	 * @return $this
	 */
	public function setStreet(?string $street): self
	{
		$this->street = $street;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getStreet(): ?string
	{
		return $this->street;
	}

	/**
	 * @param string|null $city
	 * @return $this
	 */
	public function setCity(?string $city): self
	{
		$this->city = $city;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getCity(): ?string
	{
		return $this->city;
	}


	/**
	 * @param string|null $state
	 * @return $this
	 */
	public function setState(?string $state): self
	{
		$this->state = $state;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getState(): ?string
	{
		return $this->state;
	}

	/**
	 * @param string|null $country
	 * @return $this
	 */
	public function setCountry(?string $country): self
	{
		$this->country = $country;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getCountry(): ?string
	{
		return $this->country;
	}

	/**
	 * @param string|null $zip
	 * @return $this
	 */
	public function setZip(?string $zip): self
	{
		$this->zip = $zip;

		return $this;
	}


	/**
	 * @return string|null
	 */
	public function getZip(): ?string
	{
		return $this->zip;
	}

	/**
	 * @param string|null $replyTo
	 * @return $this
	 */
	public function setReplyTo(?string $replyTo): self
	{
		$this->replyTo = $replyTo;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getReplyTo(): ?string
	{
		return $this->replyTo;
	}

	/**
	 * @param int|null $mailingListId
	 * @return $this
	 */
	public function setMailingListId(?int $mailingListId): self
	{
		$this->mailingListId = $mailingListId;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getMailingListId(): ?int
	{
		return $this->mailingListId;
	}

	private function isValidCustomFieldNumber(int $fieldNumber): bool
	{
		return $fieldNumber >= 1 && $fieldNumber <= self::CUSTOM_FIELDS_CNT;
	}

	/**
	 * @param int $fieldNumber
	 * @return bool
	 * @throws InvalidCustomFieldNumberException
	 */
	private function assertValidCustomFieldNumber(int $fieldNumber): bool
	{
		if (!$this->isValidCustomFieldNumber($fieldNumber)) {
			throw new InvalidCustomFieldNumberException(sprintf(
				'Invalid custom field number %d. Custom field number must be between %d and %d.',
				$fieldNumber,
				1,
				self::CUSTOM_FIELDS_CNT
			));
		}

		return true;
	}

	/**
	 * @param int $fieldNumber
	 * @param null|string $value
	 * @return $this
	 * @throws InvalidCustomFieldNumberException
	 */
	public function setCustomField(int $fieldNumber, ?string $value): self
	{
		$this->assertValidCustomFieldNumber($fieldNumber);

		if ($value !== null) {
			$this->customFields[$fieldNumber] = $value;
		}

		return $this;
	}

	/**
	 * @param array $customFields
	 * @return $this
	 * @throws InvalidCustomFieldNumberException
	 */
	public function setCustomFields(array $customFields): self
	{
		$this->customFields = [];

		foreach ($customFields as $fieldNumber => $value) {
			$this->setCustomField($fieldNumber, $value);
		}

		return $this;
	}

	/**
	 * @param int $fieldNumber
	 * @return $this
	 * @throws InvalidCustomFieldNumberException
	 */
	public function removeCustomField(int $fieldNumber): self
	{
		$this->assertValidCustomFieldNumber($fieldNumber);

		unset($this->customFields[$fieldNumber]);

		return $this;
	}

	/**
	 * @param int $fieldNumber
	 * @return null|string
	 * @throws InvalidCustomFieldNumberException
	 */
	public function getCustomField(int $fieldNumber): ?string
	{
		$this->assertValidCustomFieldNumber($fieldNumber);

		return $this->customFields[$fieldNumber] ?? null;
	}

	/**
	 * @return array|string[]
	 */
	public function getCustomFields(): array
	{
		return $this->customFields;
	}
}
