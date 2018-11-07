<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Exceptions;

class InvalidRpcResponseDataTypeException extends BaseRpcResponseException
{
	protected function getDefaultMessage(): string
	{
		return sprintf('Invalid data type returned while calling API method %s.', $this->method);
	}
}
