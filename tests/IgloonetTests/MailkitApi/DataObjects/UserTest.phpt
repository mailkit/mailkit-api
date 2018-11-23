<?php

use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class UserTest extends Tester\TestCase
{
	public function testBase()
	{
		$user = new \Igloonet\MailkitApi\DataObjects\User('example@example.cz');

		$user->setId(1234);
		$user->setCity('example');
		$user->setCompany('exampleCompany');
		$user->setCountry('exampleCountry');
		$user->setStatus(\Igloonet\MailkitApi\DataObjects\Enums\UserStatus::get('enabled'));
		$user->setCustomFields([1 => '1234', 2 => '5678']);
		$user->setFax('123456789');
		$user->setNickName('exampleNick');
		$user->setFirstName('exampleFirstName');
		$user->setLastName('exampleLastName');
		$user->setGender(\Igloonet\MailkitApi\DataObjects\Enums\Gender::get('M'));
		$user->setMobile('123456789');
		$user->setPhone('123456789');
		$user->setPrefix('mr');
		$user->setState('Cz');
		$user->setStreet('Example');
		$user->setVocative('example');
		$user->setZip('00000');
		$user->setReplyTo('example@reply.cz');

		Assert::same('example', $user->getCity());
		Assert::same('exampleCompany', $user->getCompany());
		Assert::same('exampleCountry', $user->getCountry());
		Assert::same(\Igloonet\MailkitApi\DataObjects\Enums\UserStatus::get('enabled'), $user->getStatus());
		Assert::same('1234', $user->getCustomField(1));
		Assert::same('5678', $user->getCustomField(2));
		Assert::same('123456789', $user->getFax());
		Assert::same('exampleNick', $user->getNickName());
		Assert::same('exampleFirstName', $user->getFirstName());
		Assert::same('exampleLastName', $user->getLastName());
		Assert::same(\Igloonet\MailkitApi\DataObjects\Enums\Gender::get('M'), $user->getGender());
		Assert::same('123456789', $user->getMobile());
		Assert::same('mr', $user->getPrefix());
		Assert::same('Cz', $user->getState());
		Assert::same('Example', $user->getStreet());
		Assert::same('example', $user->getVocative());
		Assert::same('00000', $user->getZip());
		Assert::same('example@reply.cz', $user->getReplyTo());
	}
}

(new UserTest)->run();