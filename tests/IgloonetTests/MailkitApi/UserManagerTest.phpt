<?php

namespace IgloonetTests\MailkitApi;

use Igloonet\MailkitApi\DataObjects\Enums\UserStatus;
use Igloonet\MailkitApi\DataObjects\User;
use Igloonet\MailkitApi\Managers\UsersManager;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class UserManagerTest extends MailkitTestCase
{
	/** UserManager */
	private $userManager;

	protected function setUp()
	{
		parent::setUp();
		$this->userManager = new UsersManager($this->client, ['cs'], 'cs');
	}

	public function testGetUserByEmailId()
	{
		/** @var User $user */
		$user = $this->userManager->getUserByEmailId(12345);


		Assert::same('Oslovení', $user->getVocative());
		Assert::same('titul', $user->getPrefix());
		Assert::same('Jméno', $user->getFirstName());
		Assert::same('Příjmení', $user->getLastName());
		Assert::same('přezdívka', $user->getNickName());
		Assert::same('example@example.cz', $user->getEmail());
		Assert::same('12345', $user->getPhone());
		Assert::same('12345', $user->getFax());
		Assert::same('12345', $user->getMobile());
		Assert::same('Společnost', $user->getCompany());
		Assert::same('Adresa', $user->getStreet());
		Assert::same('Město', $user->getCity());
		Assert::same('Kraj', $user->getCountry());
		Assert::same('PSČ', $user->getZip());
		Assert::same('země', $user->getState());
		Assert::same('Vlastní pole číslo 1', $user->getCustomField(1));
		Assert::same('Vlastní pole číslo 2', $user->getCustomField(2));
		Assert::same('Vlastní pole číslo 25', $user->getCustomField(25));
		Assert::same('enabled', $user->getStatus()->value);

		//Does not exist email emailId
		Assert::exception(function() {
			$this->userManager->getUserByEmailId(54321);
		}, \Igloonet\MailkitApi\Exceptions\User\UserStatusMissingEmailIdException::class);
	}

	public function testGetUsersByEmailAddress()
	{
		$users = $this->userManager->getUsersByEmailAddress('example@example.cz');

		/** @var User $user */
		foreach ($users as $user) {
			Assert::same('Oslovení', $user->getVocative());
			Assert::same('titul', $user->getPrefix());
			Assert::same('Jméno', $user->getFirstName());
			Assert::same('Příjmení', $user->getLastName());
			Assert::same('přezdívka', $user->getNickName());
			Assert::same('example@example.cz', $user->getEmail());
			Assert::same('12345', $user->getPhone());
			Assert::same('12345', $user->getFax());
			Assert::same('12345', $user->getMobile());
			Assert::same('Společnost', $user->getCompany());
			Assert::same('Adresa', $user->getStreet());
			Assert::same('Město', $user->getCity());
			Assert::same('Kraj', $user->getCountry());
			Assert::same('PSČ', $user->getZip());
			Assert::same('země', $user->getState());
			Assert::same('Vlastní pole číslo 1', $user->getCustomField(1));
			Assert::same('Vlastní pole číslo 2', $user->getCustomField(2));
			Assert::same('Vlastní pole číslo 25', $user->getCustomField(25));
			Assert::same(UserStatus::from('enabled'), $user->getStatus());
		}

		//Does not exist email
		Assert::exception(function() {
			$this->userManager->getUsersByEmailAddress('doesNotExist');
		}, \Igloonet\MailkitApi\Exceptions\User\UserStatusUnknownErrorException::class);
	}

	public function testUnsubscribeEmailAddress()
	{
		$integerValue = $this->userManager->unsubscribeEmailAddress('example@example.cz', true);

		Assert::same(282837490, $integerValue);

		//Does not exist email
		Assert::exception(function() {
			$this->userManager->unsubscribeEmailAddress('doesNotExist', true);
		}, \Igloonet\MailkitApi\Exceptions\User\UserUnsubscribtionInvalidEmailIdException::class);
	}

	public function testRevalidateEmailAddress()
	{
		$result = $this->userManager->revalidateEmailAddress('example@example.cz', true);

		Assert::true($result);

		Assert::exception(function() {
			$this->userManager->revalidateEmailAddress('doesNotExist', true);
		}, \Igloonet\MailkitApi\Exceptions\User\UserRevalidationNotUnsubscribedException::class);
	}

	public function testAddUser()
	{
		$user = new User('example@example.cz');
		$result = $this->userManager->addUser($user, 12345, true);
		Assert::true($result);

		Assert::exception(function() use ($user){
			$this->userManager->addUser($user, 54321, true);;
		}, \Igloonet\MailkitApi\Exceptions\User\UserCreationInvalidMailingListIdException::class);

	}

	public function testEditUser()
	{
		$user = new User('new@email.cz');
		$result = $this->userManager->editUser($user, 12345, true);
		Assert::true($result);

		Assert::exception(function() use ($user){
			$this->userManager->addUser($user, 54321, true);;
		}, \Igloonet\MailkitApi\Exceptions\User\UserCreationInvalidMailingListIdException::class);
	}
}

(new UserManagerTest)->run();


