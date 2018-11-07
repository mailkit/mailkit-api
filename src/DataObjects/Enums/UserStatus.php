<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;

class UserStatus extends \Consistence\Enum\Enum
{
	const ENABLED = 'enabled'; // aktivní
	const DISABLED = 'disabled'; // neaktivní
	const UNKNOWN = 'unknown'; // neznámý
	const TEMPORARY = 'temporary'; // dočasně nedostupný
	const PERMANENT = 'permanent'; // trvale nedostupný
	const UNSUBSCRIBE = 'unsubscribe'; // odhlášený
}