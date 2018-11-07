<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions\Message;

use Throwable;

class DuplicateAttachmentNameException extends \LogicException
{
	/** @var string|null  */
	private $name = null;

	public function __construct(?string $name, string $message = '', int $code = 0, Throwable $previous = null)
	{
		if (trim($message) === '') {
			$message = sprintf('Attachment with name %s already exists in this message!', $name);
		}

		parent::__construct($message, $code, $previous);
	}

	public function getName(): ?string
	{
		return $this->name;
	}
}
