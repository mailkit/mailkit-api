<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Managers;

use Igloonet\MailkitApi\DataObjects\Message;
use Igloonet\MailkitApi\Exceptions\Message\MessageSendAttachmentNotAllowedException;
use Igloonet\MailkitApi\Exceptions\Message\MessageSendException;
use Igloonet\MailkitApi\Exceptions\Message\MessageSendInvalidCampaignIdException;
use Igloonet\MailkitApi\Exceptions\Message\MessageSendInvalidMailingListIdException;
use Igloonet\MailkitApi\Exceptions\Message\MessageSendMissingCampaignIdException;
use Igloonet\MailkitApi\Exceptions\Message\MessageSendMissingMailingListIdException;
use Igloonet\MailkitApi\Exceptions\Message\MessageSendMissingSenderAddressException;
use Igloonet\MailkitApi\Exceptions\Message\MessageSendMissingSendToException;
use Igloonet\MailkitApi\Results\SendMailResult;

class MessagesManager extends BaseManager implements IMessageManager
{
	/**
	 * @throws MessageSendException
	 */
	public function sendMail(
		Message $message,
		?int $mailingListId,
		int $campaignId
	): SendMailResult {
		$main = [
			'send_to' => $message->getUser()->getEmail(),
			'subject' => $message->getSubject(),
			'message_data' => $this->encodeString($message->getBody()),
		];

		$main = $this->filterNullsFromArray($main);

		$templateVars = $message->getTemplateVars();

		array_walk_recursive($templateVars, function(&$item, $key) {
			$item = $this->encodeString((string) $item);
		});

		if (count($templateVars) > 0) {
			$main['content'] = $templateVars;
		}

		[$personal, $address, $custom] = $this->getUserDataSections($message->getUser(), null, null);
		unset($personal['email']);

		$params = [
			'mailinglist_id' => $mailingListId ?? $message->getUser()->getMailingListId(),
			'campaign_id' => $campaignId,
			'main' => $main,
			'recipient' => $personal,
			'contact' => $address,
			'custom' => $custom,
		];

		$attachments = $message->getAttachments();
		if (count($attachments) === 1) {
			$attachment = reset($attachments);
			$params['attachment'] = $this->filterNullsFromArray([
				'name' => $attachment->getName(),
				'data' => $this->encodeString($attachment->getContent()),
			]);
		} elseif (count($attachments) > 1) {
			$params['attachment'] = array_values(array_map(fn($a) => $this->filterNullsFromArray([
				'name' => $a->getName(),
				'data' => $this->encodeString($a->getContent()),
			]), $attachments));
		}

		$possibleErrors = [
			'Missing ID_mailing_list',
			'Invalid ID_mailing_list',
			'Missing ID_message',
			'Invalid ID_message',
			'Missing send_to',
			'Missing sender address',
			'Attachment is not allowed'
		];

		$rpcResponse = $this->sendRpcRequest('mailkit.sendmail', $params, $possibleErrors);

		if ($rpcResponse->isError()) {
			switch ($rpcResponse->getError()) {
				case 'Missing ID_mailing_list':
					throw new MessageSendMissingMailingListIdException($rpcResponse);
				case 'Invalid ID_mailing_list':
					throw new MessageSendInvalidMailingListIdException($rpcResponse);
				case 'Missing ID_message':
					throw new MessageSendMissingCampaignIdException($rpcResponse);
				case 'Invalid ID_message':
					throw new MessageSendInvalidCampaignIdException($rpcResponse);
				case 'Missing send_to':
					throw new MessageSendMissingSendToException($rpcResponse);
				case 'Missing sender address':
					throw new MessageSendMissingSenderAddressException($rpcResponse);
				case 'Attachment is not allowed':
					throw new MessageSendAttachmentNotAllowedException($rpcResponse);
			}
		}

		return SendMailResult::fromRpcResponse($rpcResponse);
	}
}
