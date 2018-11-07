<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Responses;

use Igloonet\MailkitApi\RPC\Exceptions\InvalidDataTypeException;

abstract class ErrorRpcResponse implements IRpcResponse
{
	/** @var int|null */
	protected $errorCode = null;

	/** @var string|null */
	protected $error = null;

	public function __construct(string $error, int $errorCode = 0)
	{
		$this->error = $error;
		$this->errorCode = $errorCode;
	}

	/**
	 * @return string
	 */
	public function getStatus(): string
	{
		return self::STATUS_ERROR;
	}

	/**
	 * @return bool
	 */
	public function isError(): bool
	{
		return true;
	}

	/**
	 * @return null|string
	 */
	public function getError(): ?string
	{
		return $this->error;
	}

	/**
	 * @return int|null
	 */
	public function getErrorCode(): ?int
	{
		return $this->errorCode;
	}

	/**
	 * @return array
	 * @throws InvalidDataTypeException
	 */
	public function getArrayValue(): array
	{
		throw new InvalidDataTypeException('Unable to get array value from error response');
	}

	/**
	 * @return string
	 * @throws InvalidDataTypeException
	 */
	public function getStringValue(): string
	{
		throw new InvalidDataTypeException('Unable to get string value from error response');
	}

	/**
	 * @return int
	 * @throws InvalidDataTypeException
	 */
	public function getIntegerValue(): int
	{
		throw new InvalidDataTypeException('Unable to get integer value from error response');
	}

	/**
	 * @return bool
	 * @throws InvalidDataTypeException
	 */
	public function getBooleanValue(): bool
	{
		throw new InvalidDataTypeException('Unable to get boolean value from error response');
	}

	/**
	 * @return array
	 * @throws InvalidDataTypeException
	 */
	public function getArrayData(): array
	{
		throw new InvalidDataTypeException('Unable to extract array data from error response');
	}

	/**
	 * @return string
	 * @throws InvalidDataTypeException
	 */
	public function getStringData(): string
	{
		throw new InvalidDataTypeException('Unable to extract string data from error response');
	}

	/**
	 * @return int
	 * @throws InvalidDataTypeException
	 */
	public function getIntegerData(): int
	{
		throw new InvalidDataTypeException('Unable to extract integer data from error response');
	}

	/**
	 * @return bool
	 * @throws InvalidDataTypeException
	 */
	public function getBooleanData(): bool
	{
		throw new InvalidDataTypeException('Unable to extract boolean data from error response');
	}
}
