<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Results;

use Igloonet\MailkitApi\DataObjects\Enums\SendMailResultStatus;
use Igloonet\MailkitApi\Exceptions\InvalidResponseException;
use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;

class SendMailResult implements IApiMethodResult
{
	/** @var int|null */
	private $emailId = null;

	/** @var int|null */
	private $sendingId = null;

	/** @var int|null */
	private $messageId = null;

	/** @var SendMailResultStatus|null */
	private $status = null;


	public function __construct(?int $emailId, ?int $sendingId, ?int $messageId, ?SendMailResultStatus $status)
	{
		$this->emailId = $emailId;
		$this->sendingId = $sendingId;
		$this->messageId = $messageId;
		$this->status = $status;
	}

	/**
	 * @return int|null
	 */
	public function getEmailId(): ?int
	{
		return $this->emailId;
	}

	/**
	 * @return int|null
	 */
	public function getSendingId(): ?int
	{
		return $this->sendingId;
	}

	/**
	 * @return int|null
	 */
	public function getMessageId(): ?int
	{
		return $this->messageId;
	}

	/**
	 * @return SendMailResultStatus|null
	 */
	public function getStatus(): ?SendMailResultStatus
	{
		return $this->status;
	}

	/**
	 * @param IRpcResponse $rpcResponse
	 * @return SendMailResult
	 */
	public static function fromRpcResponse(IRpcResponse $rpcResponse): self
	{
		$value = $rpcResponse->getArrayValue();

		foreach (['data', 'data2', 'data3', 'status'] as $field) {
			if (!array_key_exists($field, $value)) {
				throw new InvalidResponseException($rpcResponse, sprintf('Missing %s in RPC response!', $field));
			}
		}

		$emailId = is_numeric($value['data']) && (int)$value['data'] > 0 ? (int)$value['data'] : null;
		$sendingId = is_numeric($value['data2']) && (int)$value['data2'] > 0 ? (int)$value['data2'] : null;
		$messageId = is_numeric($value['data3']) && (int)$value['data3'] > 0 ? (int)$value['data3'] : null;
		$status = is_numeric($value['status']) ? SendMailResultStatus::get($value['status']): null;

		return new static($emailId, $sendingId, $messageId, $status);
	}
}
