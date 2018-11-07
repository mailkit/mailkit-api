<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects;

use Igloonet\MailkitApi\Exceptions\Message\DuplicateAttachmentNameException;

class Message
{
	/** @var User */
	private $user = null;

	/** @var string|null */
	private $subject = null;

	/** @var string|null */
	private $body = null;

	/** @var array */
	private $templateVars = [];

	/** @var array|Attachment[] */
	private $attachments = [];

	public function __construct(User $sendToUser)
	{
		$this->user = $sendToUser;
	}

	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}

	/**
	 * @param User $user
	 * @return $this
	 */
	public function setUser(User $user): self
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * @param string|null $subject
	 * @return $this
	 */
	public function setSubject(?string $subject): self
	{
		$this->subject = $subject;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getSubject(): ?string
	{
		return $this->subject;
	}

	/**
	 * @param string|null $body
	 * @return $this
	 */
	public function setBody(?string $body): self
	{
		$this->body = $body;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getBody(): ?string
	{
		return $this->body;
	}

	/**
	 * @param array $templateVars
	 * @return $this
	 */
	public function setTemplateVars(array $templateVars): self
	{
		$this->templateVars = $templateVars;

		return $this;
	}

	/**
	 * @param string $varName
	 * @param string $value
	 * @return $this
	 */
	public function setTemplateVar(string $varName, string $value): self
	{
		$this->templateVars[$varName] = $value;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getTemplateVars(): array
	{
		return $this->templateVars;
	}

	/**
	 * @param Attachment $attachment
	 * @return $this
	 */
	public function addAttachment(Attachment $attachment): self
	{
		$name = $attachment->getName();

		if (isset($this->attachments[$name])) {
			throw new DuplicateAttachmentNameException($name);
		}

		$this->attachments[$name] = $attachment;

		return $this;
	}

	/**
	 * @return array|Attachment[]
	 */
	public function getAttachments(): array
	{
		return $this->attachments;
	}
}
