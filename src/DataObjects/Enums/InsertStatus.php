<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;

enum InsertStatus: int
{
	case UPDATE = 0;
	case INSERT = 1;
	case INSERT_UNSUBSCRIBE = 2;
	case UPDATE_UNSUBSCRIBE = 3;
	case FAULT = 4;
}
