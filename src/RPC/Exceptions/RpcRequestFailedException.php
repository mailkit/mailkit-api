<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Exceptions;

class RpcRequestFailedException extends BaseRpcException
{
	/**
	 * @return string
	 */
	protected function getDefaultMessage(): string
	{
		return sprintf('API request %s failed', $this->method);
	}
}
