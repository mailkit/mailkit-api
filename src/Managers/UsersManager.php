<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Managers;

use Igloonet\MailkitApi\DataObjects\Enums\Gender;
use Igloonet\MailkitApi\DataObjects\Enums\InsertStatus;
use Igloonet\MailkitApi\DataObjects\Enums\UserStatus;
use Igloonet\MailkitApi\DataObjects\User;
use Igloonet\MailkitApi\Exceptions\User\TooManyStatusResultsException;
use Igloonet\MailkitApi\Exceptions\User\UserCreationBadEmailSyntaxException;
use Igloonet\MailkitApi\Exceptions\User\UserCreationException;
use Igloonet\MailkitApi\Exceptions\User\UserCreationInvalidMailingListIdException;
use Igloonet\MailkitApi\Exceptions\User\UserCreationInvalidTemplateIdException;
use Igloonet\MailkitApi\Exceptions\User\UserCreationMissingEmailException;
use Igloonet\MailkitApi\Exceptions\User\UserCreationMissingMailingListIdException;
use Igloonet\MailkitApi\Exceptions\User\UserCreationUnknownErrorException;
use Igloonet\MailkitApi\Exceptions\User\UserEditException;
use Igloonet\MailkitApi\Exceptions\User\UserEditInvalidEmailIdException;
use Igloonet\MailkitApi\Exceptions\User\UserEditInvalidMailingListIdException;
use Igloonet\MailkitApi\Exceptions\User\UserEditMissingEmailIdException;
use Igloonet\MailkitApi\Exceptions\User\UserEditMissingMailingListIdException;
use Igloonet\MailkitApi\Exceptions\User\UserEditUnknownErrorException;
use Igloonet\MailkitApi\Exceptions\User\UserRevalidationException;
use Igloonet\MailkitApi\Exceptions\User\UserRevalidationNotUnsubscribedException;
use Igloonet\MailkitApi\Exceptions\User\UserRevalidationUnknownErrorException;
use Igloonet\MailkitApi\Exceptions\User\UserStatusMissingEmailIdException;
use Igloonet\MailkitApi\Exceptions\User\UserStatusNoExistingEmailIdException;
use Igloonet\MailkitApi\Exceptions\User\UserStatusReceiveException;
use Igloonet\MailkitApi\Exceptions\User\UserStatusUnknownErrorException;
use Igloonet\MailkitApi\Exceptions\User\UserUnsubscribtionException;
use Igloonet\MailkitApi\Exceptions\User\UserUnsubscribtionInvalidEmailIdException;
use Igloonet\MailkitApi\Exceptions\User\UserUnsubscribtionUnknownErrorException;

class UsersManager extends BaseManager
{
	/**
	 * @return array|User[]
	 * @throws UserStatusReceiveException
	 */
	public function getUsersByEmailAddress(string $emailAddress): array
	{
		return $this->getUsers($emailAddress);
	}

	/**
	 * @throws UserStatusReceiveException|TooManyStatusResultsException
	 */
	public function getUserByEmailId(int $emailId): ?User
	{
		$userData = $this->getUsers((string)$emailId);

		if (count($userData) > 1) {
			throw new TooManyStatusResultsException(
				sprintf(
					'getUserData() returned %d results for email id %d. %d expected.',
					count($userData),
					$emailId,
					1
				)
			);
		}

		return $userData[0] ?? null;
	}

	/**
	 * @return array|User[]
	 * @throws UserStatusReceiveException
	 */
	private function getUsers(string $emailId): array
	{
		$params = [
			'email_id' => $emailId
		];
		$possibleErrors = [
			'',
			'Missing ID email',
			'Invalid ID_email'
		];

		$rpcResponse = $this->sendRpcRequest('mailkit.email.getstatus', $params, $possibleErrors);

		if ($rpcResponse->isError()) {
			switch ($rpcResponse->getError()) {
				case 'Invalid ID_email':
					throw new UserStatusNoExistingEmailIdException($rpcResponse);
				case 'Missing ID email':
					throw new UserStatusMissingEmailIdException($rpcResponse);
				default:
					throw new UserStatusUnknownErrorException($rpcResponse);
			}
		}

		$users = [];

		foreach ($rpcResponse->getArrayValue() as $userData) {
			$user = new User();
			$user->setVocative($userData['VOCATIVE'] ?? null);
			$user->setPrefix($userData['PREFIX'] ?? null);
			$user->setPhone($userData['PHONE'] ?? null);
			$user->setStreet($userData['STREET'] ?? null);
			$user->setStatus(UserStatus::from($userData['STATUS']));
			$user->setCity($userData['CITY'] ?? null);
			$user->setCompany($userData['COMPANY'] ?? null);
			$user->setFirstName($userData['FIRST_NAME'] ?? null);
			$user->setLastName($userData['LAST_NAME'] ?? null);
			$user->setEmail($userData['EMAIL'] ?? null);
			$user->setFax($userData['FAX'] ?? null);
			$user->setId(isset($userData['ID_EMAIL']) ? (int)$userData['ID_EMAIL'] : null);
			$user->setState($userData['STATE'] ?? null);
			$user->setZip($userData['ZIP'] ?? null);
			$user->setNickName($userData['NICK_NAME'] ?? null);
			$user->setReplyTo($userData['REPLY_TO'] ?? null);
			$user->setGender(!empty($userData['GENDER']) ? Gender::from($userData['GENDER']) : null);
			$user->setCountry($userData['COUNTRY'] ?? null);
			$user->setMobile($userData['MOBILE'] ?? null);
			$user->setMailingListId(isset($userData['ID_MAILING_LIST']) ? (int)$userData['ID_MAILING_LIST'] : null);

			for ($i = 1; $i <= User::CUSTOM_FIELDS_CNT; $i++) {
				$user->setCustomField($i, $userData['CUSTOM_'.$i] ?? null);
			}

			$users[] = $user;
		}

		return $users;
	}

	/**
	 * @throws UserUnsubscribtionException
	 */
	public function unsubscribeEmailAddress(
		string $emailAddress,
		bool $sendOptOut,
		?int $campaignMessageId = null
	): int {
		return $this->unsubscribeUser($emailAddress, $sendOptOut, $campaignMessageId);
	}

	/**
	 * @throws UserUnsubscribtionException
	 */
	public function unsubscribeEmailId(int $emailId, bool $sendOptOut, ?int $campaignMessageId = null): int
	{
		return $this->unsubscribeUser((string)$emailId, $sendOptOut, $campaignMessageId);
	}

	/**
	 * @throws UserUnsubscribtionException
	 */
	private function unsubscribeUser(string $emailId, bool $sendOptOut, ?int $campaignMessageId = null): int
	{
		$params = [
			'ID_email' => $emailId,
			'ID_send_message' => $campaignMessageId,
			'send_optout' => $sendOptOut
		];

		$possibleErrors = [
			'Invalid ID_email or email'
		];

		$rpcResponse = $this->sendRpcRequest('mailkit.email.unsubscribe', $params, $possibleErrors);

		if ($rpcResponse->isError()) {
			switch ($rpcResponse->getError()) {
				case 'Invalid ID_email or email':
					throw new UserUnsubscribtionInvalidEmailIdException($rpcResponse);
				default:
					throw new UserUnsubscribtionUnknownErrorException($rpcResponse);
			}
		}

		return (int)$rpcResponse->getArrayValue()['ID_email'];
	}

	public function revalidateEmailAddress(
		string $emailAddress,
		bool $agreement,
		?string $channel = null,
		?string $language = null,
		?int $campaignMessageId = null
	): bool {
		return $this->revalidateUser($emailAddress, $agreement, $channel, $language, $campaignMessageId);
	}

	public function revalidateEmailId(
		int $emailId,
		bool $agreement,
		?string $channel = null,
		?string $language = null,
		?int $campaignMessageId = null
	): bool {
		return $this->revalidateUser((string)$emailId, $agreement, $channel, $language, $campaignMessageId);
	}

	/**
	 * @throws UserRevalidationException
	 */
	private function revalidateUser(
		string $emailId,
		bool $agreement,
		?string $channel = null,
		?string $language = null,
		?int $campaignMessageId = null
	): bool {
		$params = [
			'ID_email' => $emailId,
			'ID_message' => $campaignMessageId,
			'agreement' => $this->getBooleanString($agreement),
			'channel' => $channel,
			'dummy' => null,
			'language' => $this->validateLanguage($language)
		];

		$possibleErrors = [
			'/^.+ is not unsubscribed$/'
		];

		$rpcResponse = $this->sendRpcRequest('mailkit.email.revalidate', $params, $possibleErrors);

		if ($rpcResponse->isError()) {
			if (str_ends_with($rpcResponse->getError(), ' is not unsubscribed')) {
				throw new UserRevalidationNotUnsubscribedException($rpcResponse);
			} else {
				throw new UserRevalidationUnknownErrorException($rpcResponse);
			}
		}

		$result = $rpcResponse->getArrayValue()['error'] ?? '';

		return ($agreement === true && $result === 'Email has been revalidated') ||
			($agreement === false && $result === 'Sent subscribe email');
	}

	/**
	 * @throws UserCreationException
	 */
	public function addUser(
		User $user,
		?int $mailingListId,
		bool $doubleOptIn,
		?string $returnUrl = null,
		?string $templateId = null
	): bool {
		[$personal, $address, $custom] = $this->getUserDataSections($user, $returnUrl, $templateId);

		$params = [
			'ID_user_list' => $mailingListId ?? $user->getMailingListId(),
			'confirm' => $this->getBooleanString($doubleOptIn),
			'personal' => $personal,
			'address' => $address,
			'custom' => $custom,
		];

		$possibleErrors = [
			'Missing email',
			'Bad email syntax',
			'Missing ID_mailing_list',
			'Invalid ID_mailing_list',
			'Invalid ID_template'
		];

		$rpcResponse = $this->sendRpcRequest('mailkit.mailinglist.adduser', $params, $possibleErrors);

		if ($rpcResponse->isError()) {
			switch ($rpcResponse->getError()) {
				case 'Missing email':
					throw new UserCreationMissingEmailException($rpcResponse);
				case 'Bad email syntax':
					throw new UserCreationBadEmailSyntaxException($rpcResponse, $user->getEmail());
				case 'Missing ID_mailing_list':
					throw new UserCreationMissingMailingListIdException($rpcResponse);
				case 'Invalid ID_mailing_list':
					throw new UserCreationInvalidMailingListIdException($rpcResponse);
				case 'Invalid ID_template':
					throw new UserCreationInvalidTemplateIdException($rpcResponse);
				default:
					throw new UserCreationUnknownErrorException($rpcResponse);
			}
		}

		$value = $rpcResponse->getArrayValue();

		if (isset($value['ID_email']) && is_numeric($value['ID_email'])) {
			$user->setId((int)$value['ID_email']);
		}

		if (isset($value['status']) && is_numeric($value['status'])) {
			$user->setInsertStatus(InsertStatus::from((int)$value['status']));
		}

		return true;
	}

	/**
	 * @throws UserEditException
	 */
	public function editUser(
		User $user,
		?int $mailingListId,
		bool $keepValues
	): bool {
		[$personal, $address, $custom] = $this->getUserDataSections($user, null, null);
		unset($personal['email']);

		$params = [
			'ID_user_list' => $mailingListId ?? $user->getMailingListId(),
			'ID_email' => $user->getEmail(),
			'personal' => $personal,
			'address' => $address,
			'custom' => $custom,
		];

		$possibleErrors = [
			'Missing ID_email',
			'Invalid ID_email',
			'Missing ID_user_list',
			'Invalid ID_user_list',
			'Missing ID_mailing_list',
			'Invalid ID_mailing_list',
		];

		$rpcResponse = $this->sendRpcRequest('mailkit.mailinglist.edituser', $params, $possibleErrors);

		if ($rpcResponse->isError()) {
			switch ($rpcResponse->getError()) {
				case 'Missing ID_email':
					throw new UserEditMissingEmailIdException($rpcResponse);
				case 'Invalid ID_email':
					throw new UserEditInvalidEmailIdException($rpcResponse);
				case 'Missing ID_user_list':
				case 'Missing ID_mailing_list':
					throw new UserEditMissingMailingListIdException($rpcResponse);
				case 'Invalid ID_user_list':
				case 'Invalid ID_mailing_list':
					throw new UserEditInvalidMailingListIdException($rpcResponse);
				default:
					throw new UserEditUnknownErrorException($rpcResponse);
			}
		}

		$value = $rpcResponse->getArrayValue();
		$message = trim($value['error'] ?? '');

		if ($message === 'OK') {
			return true;
		}

		throw new UserEditUnknownErrorException($rpcResponse, $message);
	}
}
