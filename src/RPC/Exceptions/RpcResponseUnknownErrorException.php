<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Exceptions;

use Throwable;

class RpcResponseUnknownErrorException extends BaseRpcException
{
	/** @var string  */
	protected $error = '';

	/** @var array */
	protected $possibleErrors = [];

	public function __construct(
		string $method,
		array $requestData,
		string $error,
		array $possibleErrors,
		string $message = '',
		int $code = 0,
		Throwable $previous = null
	) {
		$this->error = $error;
		$this->possibleErrors = $possibleErrors;

		parent::__construct($method, $requestData, $message, $code, $previous);
	}

	protected function getDefaultMessage(): string
	{
		return sprintf(
			'Unknown RPC error returned: %s. Possible errors: %s',
			$this->error,
			implode(',', $this->possibleErrors)
		);
	}
}
