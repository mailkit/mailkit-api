<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;


class UnsubscribeMethod extends \Consistence\Enum\Enum
{
	const LINK_IN_MAIL = 'link_in_mail';
	const MANUAL = 'manual';
	const SPAM_REPORT = 'spam_report';
	const LIST_UNSUBSCRIBE_MAIL = 'list-unsubscribe_mail';
	const API_UNSUBSCRIBE = 'api_unsubscribe';
	const LIST_UNSUBSCRIBE_ONECLICK = 'list-unsubscribe_oneclick';
	const TIMEOUT = 'timeout';
}