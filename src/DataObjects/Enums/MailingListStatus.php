<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;


class MailingListStatus extends \Consistence\Enum\Enum
{
	const STATUS_ENABLED = 'enabled';
	const STATUS_DISABLED = 'disabled';
}