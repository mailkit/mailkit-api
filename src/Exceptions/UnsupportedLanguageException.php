<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions;

use Throwable;

class UnsupportedLanguageException extends InvalidArgumentException
{
	/** @var string */
	private $language = null;

	public function __construct(string $language, string $message = '', int $code = 0, Throwable $previous = null)
	{
		$this->language = $language;

		if (trim($message) === '') {
			$message = sprintf('Language %s is not supported by Mailkit API', $language);
		}

		parent::__construct($message, $code, $previous);
	}
}
