<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions\Message;

use Igloonet\MailkitApi\Exceptions\IOException;
use Throwable;

class AttachmentFileNotReadableException extends IOException implements AttachmentException
{
	/** @var null|string  */
	private $filePath = null;

	public function __construct(string $filePath, string $message = '', int $code = 0, Throwable $previous = null)
	{
		$this->filePath = $filePath;

		if (trim($message) === '') {
			$message = sprintf('File %s is not readable!', $filePath);
		}

		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return string|null
	 */
	public function getFilePath(): ?string
	{
		return $this->filePath;
	}
}
