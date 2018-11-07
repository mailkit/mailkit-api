<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions\Message;

use Igloonet\MailkitApi\Exceptions\RpcResponseErrorException;

abstract class MessageSendException extends RpcResponseErrorException implements MessageException
{
}
