<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Managers;

use Igloonet\MailkitApi\DataObjects\Enums\MailingListStatus;
use Igloonet\MailkitApi\DataObjects\MailingList;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListCreationUnknownErrorException;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListDeletionException;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListDeletionUnknownErrorException;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListExistsException;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListInvalidIdException;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListMissingIdException;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListMissingNameException;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListNotFoundException;
use Igloonet\MailkitApi\Exceptions\MailingList\MailingListsLoadException;

class MailingListsManager extends BaseManager
{
	/**
	 * @return array|MailingList[]
	 */
	public function getMailingLists(): array
	{
		$rpcResponse = $this->sendRpcRequest('mailkit.mailinglist.list', [], []);

		if ($rpcResponse->isError()) {
			throw new MailingListsLoadException($rpcResponse);
		}

		$mailingLists = [];

		foreach ($rpcResponse->getArrayValue() as $mailingListData) {
			$mailingLists[] = MailingList::create(
				(int)$mailingListData['ID_USER_LIST'],
				$mailingListData['NAME'],
				MailingListStatus::from($mailingListData['STATUS']),
				$mailingListData['DESCRIPTION']
			);
		}

		return $mailingLists;
	}

	public function createMailingList(string $name, ?string $description = null): MailingList
	{
		$params = [
			'name' => $name
		];

		if ($description !== null) {
			$params['description'] = $description;
		}

		$possibleErrors = [
			'Missing name of mailing list',
			'Mailing list exist'
		];

		$rpcResponse = $this->sendRpcRequest('mailkit.mailinglist.create', $params, $possibleErrors);

		if ($rpcResponse->isError()) {
			switch ($rpcResponse->getError()) {
				case 'Missing name of mailing list':
					throw new MailingListMissingNameException($rpcResponse);
				case 'Mailing list exist':
					throw new MailingListExistsException($rpcResponse);
				default:
					throw new MailingListCreationUnknownErrorException($rpcResponse);
			}
		}

		$mailingList = new MailingList();
		$mailingList->setId($rpcResponse->getIntegerData());
		$mailingList->setName($name);
		$mailingList->setDescription($description);

		return $mailingList;
	}

	public function flushMailingList(int $id): bool
	{
		return $this->deleteMailingList($id, true);
	}

	/**
	 * @throws MailingListDeletionException
	 */
	public function deleteMailingList(int $id, bool $keepList = false): bool
	{
		$params = [
			'ID_user_list' => $id,
			'keep_list' => $this->getBooleanString($keepList)
		];

		$possibleErrors = [
			'Missing ID_user_list',
			'Invalid ID_user_list'
		];

		$rpcResponse = $this->sendRpcRequest('mailkit.mailinglist.delete', $params, $possibleErrors);

		if ($rpcResponse->isError()) {
			switch ($rpcResponse->getError()) {
				case 'Missing ID_user_list':
					throw new MailingListMissingIdException($rpcResponse);
				case 'Invalid ID_user_list':
					throw new MailingListInvalidIdException($rpcResponse);
				default:
					throw new MailingListDeletionUnknownErrorException($rpcResponse);
			}
		}

		$message = trim($rpcResponse->getStringData());

		if ($message === 'OK') {
			return true;
		}

		throw new MailingListDeletionUnknownErrorException($rpcResponse, $message);
	}

	/**
	 * @throws MailingListNotFoundException|MailingListsLoadException|MailingListDeletionException
	 */
	public function deleteMailingListByName(string $name, bool $keepList = false): bool
	{
		$mailingList = $this->getMailingListByName($name);

		return $this->deleteMailingList($mailingList->getId(), $keepList);
	}

	/**
	 * @throws MailingListNotFoundException|MailingListsLoadException
	 */
	public function getMailingListByName(string $name): MailingList
	{
		if (($mailingList = $this->findMailingListByName($name)) === null) {
			throw new MailingListNotFoundException(sprintf('Mailing list "%s" was not found!', $name));
		}

		return $mailingList;
	}

	public function findMailingListByName(string $name): ?MailingList
	{
		foreach ($this->getMailingLists() as $mailingList) {
			if (strcasecmp($mailingList->getName(), $name) === 0) {
				return $mailingList;
			}
		}

		return null;
	}
}
