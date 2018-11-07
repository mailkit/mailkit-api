<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Exceptions\User;

use Igloonet\MailkitApi\Exceptions\InvalidArgumentException;

class InvalidCustomFieldNumberException extends InvalidArgumentException implements UserException
{
}
