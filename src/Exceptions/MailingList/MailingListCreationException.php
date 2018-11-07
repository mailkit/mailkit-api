<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions\MailingList;

use Igloonet\MailkitApi\Exceptions\RpcResponseErrorException;

abstract class MailingListCreationException extends RpcResponseErrorException implements MailingListException
{
}
