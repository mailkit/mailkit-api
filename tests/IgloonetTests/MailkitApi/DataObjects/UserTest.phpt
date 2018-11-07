<?php

require_once __DIR__ . '/../../bootstrap.php';

class UserTest extends Tester\TestCase
{
	public function testBase()
	{
		$user = new \Igloonet\MailkitApi\DataObjects\User('example@example.cz');
//
//		$user->setId(1234);
//		$user->setCity('example');
//		$user->setCompany('exampleCompany');
//		$user->setCountry('exampleCOuntry');
//		$user->setStatus(\Igloonet\MailkitApi\DataObjects\User::STATUS_ENABLED);
//		$user->setCustomFields(['custom1'=>1234, 'custom2'=>5678]);
//		$user->setFax('123456789');
//		$user->setNickName('exampleNick');
//		$user->setFirstName('exampleFirstName');
//		$user->setLastName('exampleLastName');
//		$user->setGender(); //@todo enum
//		$user->setMobile('123456789');
//		$user->setPhone('123456789');
//		$user->setPrefix();
//		$user->setState();
//		$user->setStreet();
//		$user->setVocative();
//		$user->setZip();
//		$user->setReplyTo();


		\Tester\Assert::same('d','d');
	}
}

(new UserTest)->run();