<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;

enum UserStatus: string
{
	case ENABLED = 'enabled';
	case DISABLED = 'disabled';
	case UNKNOWN = 'unknown';
	case TEMPORARY = 'temporary';
	case PERMANENT = 'permanent';
	case UNSUBSCRIBE = 'unsubscribe';
}
