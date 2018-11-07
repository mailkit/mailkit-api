<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Responses;

abstract class SuccessRpcResponse implements IRpcResponse
{
	public function getStatus(): string
	{
		return self::STATUS_SUCCESS;
	}

	public function isError(): bool
	{
		return false;
	}

	public function getError(): ?string
	{
		return null;
	}

	public function getErrorCode(): ?int
	{
		return null;
	}
}
