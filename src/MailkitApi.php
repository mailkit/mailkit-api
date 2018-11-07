<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi;

use Igloonet\MailkitApi\Managers\MessagesManager;
use Igloonet\MailkitApi\Managers\UsersManager;
use Igloonet\MailkitApi\Managers\MailingListsManager;

class MailkitApi
{
	/** @var MailingListsManager */
	private $mailingListsManager = null;

	/** @var UsersManager */
	private $usersManager = null;

	/** @var MessagesManager */
	private $messagesManager = null;

	public function __construct(
		MailingListsManager $mailingListsManager,
		UsersManager $usersManager,
		MessagesManager $messagesManager
	) {
		$this->mailingListsManager = $mailingListsManager;
		$this->usersManager = $usersManager;
		$this->messagesManager = $messagesManager;
	}

	/**
	 * @return MailingListsManager
	 */
	public function getMailingListsManager(): MailingListsManager
	{
		return $this->mailingListsManager;
	}

	/**
	 * @return UsersManager
	 */
	public function getUsersManager(): UsersManager
	{
		return $this->usersManager;
	}

	/**
	 * @return MessagesManager
	 */
	public function getMessagesManager(): MessagesManager
	{
		return $this->messagesManager;
	}
}
