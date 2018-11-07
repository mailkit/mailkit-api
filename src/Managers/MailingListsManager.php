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
use Nette\Utils\Strings;

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
				$mailingListData['ID_USER_LIST'],
				$mailingListData['NAME'],
				MailingListStatus::get($mailingListData['STATUS']),
				$mailingListData['DESCRIPTION']
			);
		}

		return $mailingLists;
	}

	/**
	 * @param string $name
	 * @param string|null $description
	 * @return MailingList
	 */
	public function createMailingList(string $name, string $description = null): MailingList
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
					break;
				case 'Mailing list exist':
					throw new MailingListExistsException($rpcResponse);
					break;
				default:
					throw new MailingListCreationUnknownErrorException($rpcResponse);
					break;
			}
		}

		$mailingList = new MailingList();
		$mailingList->setId($rpcResponse->getIntegerData());
		$mailingList->setName($name);
		$mailingList->setDescription($description);

		return $mailingList;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function flushMailingList(int $id): bool
	{
		return $this->deleteMailingList($id, true);
	}

	/**
	 * @param int $id
	 * @param bool $keepList
	 * @return bool
	 * @throws MailingListDeletionException
	 */
	public function deleteMailingList(int $id, $keepList = false): bool
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
					break;
				case 'Invalid ID_user_list':
					throw new MailingListInvalidIdException($rpcResponse);
					break;
				default:
					throw new MailingListDeletionUnknownErrorException($rpcResponse);
					break;
			}
		}

		$message = trim($rpcResponse->getStringData());

		if ($message === 'OK') {
			return true;
		}

		throw new MailingListDeletionUnknownErrorException($rpcResponse, $message);
	}

	/**
	 * @param string $name
	 * @param bool $keepList
	 * @return bool
	 * @throws MailingListNotFoundException|MailingListsLoadException|MailingListDeletionException
	 */
	public function deleteMailingListByName(string $name, bool $keepList = false): bool
	{
		$mailingList = $this->getMailingListByName($name);

		return $this->deleteMailingList($mailingList->getId(), $keepList);
	}

	/**
	 * @param string $name
	 * @return MailingList
	 * @throws MailingListNotFoundException|MailingListsLoadException
	 */
	public function getMailingListByName(string $name): MailingList
	{
		if (($mailingList = $this->findMailingListByName($name)) === null) {
			throw new MailingListNotFoundException(sprintf('Mailing list "%s" was not found!', $name));
		}

		return $mailingList;
	}

	/**
	 * @param string $name
	 * @return MailingList|null
	 */
	public function findMailingListByName(string $name): ?MailingList
	{
		foreach ($this->getMailingLists() as $mailingList) {
			if (Strings::compare($mailingList->getName(), $name)) {
				return $mailingList;
			}
		}

		$nameLower = Strings::lower($name);
		foreach ($this->getMailingLists() as $mailingList) {
			if (Strings::compare(Strings::lower($mailingList->getName()), $nameLower)) {
				return $mailingList;
			}
		}

		return null;
	}
}
