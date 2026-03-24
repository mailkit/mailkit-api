<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\DataObjects\Enums;

enum UnsubscribeMethod: string
{
	case LINK_IN_MAIL = 'link_in_mail';
	case MANUAL = 'manual';
	case SPAM_REPORT = 'spam_report';
	case LIST_UNSUBSCRIBE_MAIL = 'list-unsubscribe_mail';
	case API_UNSUBSCRIBE = 'api_unsubscribe';
	case LIST_UNSUBSCRIBE_ONECLICK = 'list-unsubscribe_oneclick';
	case TIMEOUT = 'timeout';
}
