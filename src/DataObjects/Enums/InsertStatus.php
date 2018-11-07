<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;

class InsertStatus extends \Consistence\Enum\Enum
{
	const UPDATE = 0;
	const INSERT = 1;
	const INSERT_UNSUBSCRIBE = 2;
	const UPDATE_UNSUBSCRIBE = 3;
	const FAULT = 4;
}