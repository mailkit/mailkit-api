<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions;

use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;
use Throwable;

class InvalidResponseException extends \RuntimeException implements MailkitApiException
{
	/** @var IRpcResponse|null  */
	private $response = null;

	public function __construct(IRpcResponse $response, string $message = '', int $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->response = $response;
	}
}
