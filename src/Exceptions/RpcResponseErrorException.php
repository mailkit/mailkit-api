<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions;

use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;
use Throwable;

abstract class RpcResponseErrorException extends \RuntimeException implements MailkitApiException
{
	/** @var IRpcResponse */
	private $rpcResponse = null;

	public function __construct(
		IRpcResponse $rpcResponse,
		string $message = '',
		int $code = 0,
		Throwable $previous = null
	) {
		$this->rpcResponse = $rpcResponse;

		if (trim($message) === '') {
			$message = $rpcResponse->getError();
			$code = $rpcResponse->getErrorCode();
		}

		parent::__construct($message, $code, $previous);
	}

	public function getRpcResponse(): ?IRpcResponse
	{
		return $this->rpcResponse;
	}
}
