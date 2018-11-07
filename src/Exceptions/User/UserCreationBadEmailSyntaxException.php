<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions\User;

use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;
use Throwable;

class UserCreationBadEmailSyntaxException extends UserCreationException
{
	/** @var null|string  */
	private $emailAddress = null;

	public function __construct(
		IRpcResponse $rpcResponse,
		string $emailAddress,
		?string $message = '',
		int $code = 0,
		Throwable $previous = null
	) {
		$this->emailAddress = $emailAddress;

		parent::__construct($rpcResponse, $message, $code, $previous);
	}
}
