<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Responses;

interface IRpcResponse
{
	public const STATUS_SUCCESS = 'success';
	public const STATUS_ERROR = 'error';

	public function getStatus(): string;

	public function isError(): bool;

	public function getErrorCode(): ?int;

	public function getError(): ?string;

	public function getArrayValue(): array;

	public function getStringValue(): string;

	public function getIntegerValue(): int;

	public function getBooleanValue(): bool;

	public function getArrayData(): array;

	public function getStringData(): string;

	public function getIntegerData(): int;

	public function getBooleanData(): bool;
}
