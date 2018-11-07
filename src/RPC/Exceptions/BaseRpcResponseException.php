<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Exceptions;

use Throwable;

abstract class BaseRpcResponseException extends BaseRpcException
{
	/** @var mixed */
	private $responseData = null;

	public function __construct(
		string $method,
		array $requestData,
		array $responseData,
		string $message = '',
		int $code = 0,
		Throwable $previous = null
	) {
		$this->responseData = $responseData;

		parent::__construct($method, $requestData, $message, $code, $previous);
	}

	/**
	 * @return mixed
	 */
	public function getResponseData()
	{
		return $this->responseData;
	}
}
