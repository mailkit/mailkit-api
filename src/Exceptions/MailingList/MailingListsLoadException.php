<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions\MailingList;

use Igloonet\MailkitApi\Exceptions\RpcResponseErrorException;

class MailingListsLoadException extends RpcResponseErrorException implements MailingListException
{
}
