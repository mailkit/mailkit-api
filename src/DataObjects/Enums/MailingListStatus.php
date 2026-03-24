<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;

enum MailingListStatus: string
{
	case STATUS_ENABLED = 'enabled';
	case STATUS_DISABLED = 'disabled';
}
