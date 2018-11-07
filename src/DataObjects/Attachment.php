<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects;

use Igloonet\MailkitApi\Exceptions\Message\AttachmentEmptyContentException;
use Igloonet\MailkitApi\Exceptions\Message\AttachmentFileNotFoundException;
use Igloonet\MailkitApi\Exceptions\Message\AttachmentFileNotReadableException;

class Attachment
{
	/** @var string */
	private $name = null;

	/** @var string */
	private $filePath = null;

	/** @var string */
	private $content = null;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @return string|null
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		if ($this->content !== null) {
			return $this->content;
		}

		if ($this->filePath !== null) {
			if (!file_exists($this->filePath)) {
				throw new AttachmentFileNotFoundException($this->filePath);
			}

			if (!is_readable($this->filePath)) {
				throw new AttachmentFileNotReadableException($this->filePath);
			}

			$fileContent = file_get_contents($this->filePath);

			if ($fileContent !== false) {
				return $fileContent;
			}
		}

		throw new AttachmentEmptyContentException(
			sprintf('Content of attachment %s can not be empty!', $this->name)
		);
	}

	/**
	 * @param string $filePath
	 * @param string|null $name
	 * @return Attachment
	 */
	public static function fromFile(string $filePath, string $name = null): self
	{
		if (trim($name ?? '') === '') {
			$name = pathinfo($filePath, PATHINFO_BASENAME);
		}

		$attachment = new static($name);
		$attachment->filePath = $filePath;

		return $attachment;
	}

	/**
	 * @param string $content
	 * @param string $name
	 * @return Attachment
	 */
	public static function fromString(string $content, string $name): self
	{
		$attachment = new static($name);

		$attachment->content = $content;

		return $attachment;
	}
}
