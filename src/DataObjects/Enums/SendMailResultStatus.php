<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;

class SendMailResultStatus extends \Consistence\Enum\Enum
{
	const UPDATE = 0;
	const INSERT = 1;
	const INSERT_UNSUBSCRIBE = 2;
	const UPDATE_UNSUBSCRIBE = 3;
	const FAULT = 4;
	const UPDATE_NOT_SENT = 6;
	const INSERT_NOT_SENT = 7;
}