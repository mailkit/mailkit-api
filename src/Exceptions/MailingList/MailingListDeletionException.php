<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions\MailingList;

use Igloonet\MailkitApi\Exceptions\RpcResponseErrorException;

abstract class MailingListDeletionException extends RpcResponseErrorException implements MailingListException
{
}
