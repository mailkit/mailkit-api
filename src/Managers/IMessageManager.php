<?php

namespace Igloonet\MailkitApi\Managers;

use Igloonet\MailkitApi\DataObjects\Message;
use Igloonet\MailkitApi\Results\SendMailResult;

interface IMessageManager
{
	public function sendMail(
		Message $message,
		?int $mailingListId,
		int $campaignId
	): SendMailResult;

}