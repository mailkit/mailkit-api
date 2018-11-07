<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Responses;

use Igloonet\MailkitApi\RPC\Exceptions\InvalidDataTypeException;
use Igloonet\MailkitApi\RPC\Exceptions\UnsupportedDataTypeException;

class XmlSuccessRpcResponse extends SuccessRpcResponse
{
	/** @var array|null */
	protected $arrayValue = null;

	/** @var string|null */
	protected $stringValue = null;

	/** @var int|null */
	protected $integerValue = null;

	/** @var bool|null */
	protected $booleanValue = null;

	public function __construct(?array $arrayValue, ?string $stringValue, ?int $integerValue, ?bool $booleanValue)
	{
		$this->arrayValue = $arrayValue;
		$this->stringValue = $stringValue;
		$this->integerValue = $integerValue;
		$this->booleanValue = $booleanValue;
	}

	/**
	 * @param array|string|int|bool $data
	 * @return XmlSuccessRpcResponse
	 * @throws UnsupportedDataTypeException
	 */
	public static function createFromResponseData($data): self
	{
		if (is_array($data)) {
			return new static($data, null, null, null);
		} elseif (is_string($data)) {
			return new static(null, $data, null, null);
		} elseif (is_numeric($data)) {
			return new static(null, null, $data, null);
		} elseif (is_bool($data)) {
			return new static(null, null, null, $data);
		} else {
			throw new UnsupportedDataTypeException(sprintf(
				'%s does not support data type %s. Supports only array, string or integer data type.',
				static::class,
				is_object($data) ? get_class($data) : gettype($data)
			));
		}
	}

	/**
	 * @return array
	 * @throws InvalidDataTypeException
	 */
	public function getArrayValue(): array
	{
		if ($this->arrayValue === null) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to get array value from response %s',
				print_r($this, true)
			));
		}

		return $this->arrayValue;
	}

	/**
	 * @return string
	 * @throws InvalidDataTypeException
	 */
	public function getStringValue(): string
	{
		if ($this->stringValue === null) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to get string value from response %s',
				print_r($this, true)
			));
		}

		return $this->stringValue;
	}

	/**
	 * @return int
	 * @throws InvalidDataTypeException
	 */
	public function getIntegerValue(): int
	{
		if ($this->integerValue === null) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to get integer value from response %s',
				print_r($this, true)
			));
		}

		return $this->integerValue;
	}

	/**
	 * @return bool
	 * @throws InvalidDataTypeException
	 */
	public function getBooleanValue(): bool
	{
		if ($this->booleanValue === null) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to get boolean value from response %s',
				print_r($this, true)
			));
		}

		return $this->booleanValue;
	}

	/**
	 * @return array
	 * @throws InvalidDataTypeException
	 */
	public function getArrayData(): array
	{
		if ($this->arrayValue === null || !isset($this->arrayValue['data']) || !is_array($this->arrayValue['data'])) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to extract array data from response %s',
				print_r($this, true)
			));
		}

		return $this->arrayValue['data'];
	}

	/**
	 * @return string
	 * @throws InvalidDataTypeException
	 */
	public function getStringData(): string
	{
		if ($this->arrayValue === null || !isset($this->arrayValue['data']) || !is_string($this->arrayValue['data'])) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to extract string data from response %s',
				print_r($this, true)
			));
		}

		return $this->arrayValue['data'];
	}

	/**
	 * @return int
	 * @throws InvalidDataTypeException
	 */
	public function getIntegerData(): int
	{
		if ($this->arrayValue === null || !isset($this->arrayValue['data']) || !is_numeric($this->arrayValue['data'])) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to extract integer data from response %s',
				print_r($this, true)
			));
		}

		return (int)$this->arrayValue['data'];
	}

	/**
	 * @return bool
	 * @throws InvalidDataTypeException
	 */
	public function getBooleanData(): bool
	{
		if ($this->arrayValue === null || !isset($this->arrayValue['data']) || !is_bool($this->arrayValue['data'])) {
			throw new InvalidDataTypeException(sprintf(
				'Unable to extract boolean data from response %s',
				print_r($this, true)
			));
		}

		return $this->arrayValue['data'];
	}
}
