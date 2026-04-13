<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Exceptions;

use Throwable;

abstract class BaseRpcException extends \RuntimeException implements RpcException
{
	/** @var string */
	protected $method = null;

	/** @var array<string, mixed> */
	protected $requestData = null;

	/**
	 * @param array<string, mixed> $requestData
	 */
	public function __construct(
		string $method,
		array $requestData,
		string $message = '',
		int $code = 0,
		?Throwable $previous = null
	) {
		parent::__construct($message, $code, $previous);

		$this->method = $method;
		$this->requestData = $requestData;

		if (trim($this->message) === '') {
			$this->message = $this->getDefaultMessage();
		}
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getRequestData(): array
	{
		return $this->requestData;
	}

	abstract protected function getDefaultMessage(): string;
}
