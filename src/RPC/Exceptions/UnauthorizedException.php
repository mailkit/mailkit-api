<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Exceptions;

class UnauthorizedException extends BaseRpcException
{
	protected function getDefaultMessage(): string
	{
		return sprintf('API request unauthorized for method %s', $this->method);
	}
}
