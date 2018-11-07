<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Responses;

use Igloonet\MailkitApi\RPC\Exceptions\InvalidDataTypeException;

class JsonSuccessRpcResponse extends SuccessRpcResponse
{
	/** @var array|null */
	protected $data = null;

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @return array
	 * @throws InvalidDataTypeException
	 */
	public function getArrayValue(): array
	{
		if ($this->data === null) {
			throw new InvalidDataTypeException('Unable to get array value from response');
		}

		return $this->data;
	}

	public function getStringValue(): string
	{
		throw new InvalidDataTypeException();
	}

	public function getIntegerValue(): int
	{
		throw new InvalidDataTypeException();
	}

	public function getBooleanValue(): bool
	{
		throw new InvalidDataTypeException();
	}


	public function getArrayData(): array
	{
		if (!isset($this->data['data']) || !is_array($this->data['data'])) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to extract array data from response %s',
				print_r($this->data, true)
			));
		}

		return $this->data['data'];
	}


	/**
	 * @return string
	 * @throws InvalidDataTypeException
	 */
	public function getStringData(): string
	{
		if (!isset($this->data['data']) || !is_string($this->data['data'])) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to extract string data from response %s',
				print_r($this->data, true)
			));
		}

		return $this->data['data'];
	}

	/**
	 * @return int
	 * @throws InvalidDataTypeException
	 */
	public function getIntegerData(): int
	{
		if (!isset($this->data['data']) || !is_string($this->data['data'])) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to extract integer data from response %s',
				print_r($this->data, true)
			));
		}

		return (int)$this->data['data'];
	}

	/**
	 * @return bool
	 * @throws InvalidDataTypeException
	 */
	public function getBooleanData(): bool
	{
		if (!isset($this->data['data']) || !is_bool($this->data['data'])) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to extract boolean data from response %s',
				print_r($this->data, true)
			));
		}

		return $this->data['data'];
	}
}
