<?php

use Igloonet\MailkitApi\DataObjects\Enums\MailingListStatus;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class MailingListTest extends Tester\TestCase
{
	public function testCreate()
	{
		$mailingList  = \Igloonet\MailkitApi\DataObjects\MailingList::create(
			12345,
			'mailingList',
			MailingListStatus::get(MailingListStatus::STATUS_ENABLED),
			'description'
		);

		Assert::same(12345, $mailingList->getId());
		Assert::same('mailingList', $mailingList->getName());
		Assert::same('description', $mailingList->getDescription());
	}

	public function testSetters()
	{
		$mailingList  = \Igloonet\MailkitApi\DataObjects\MailingList::create(
			12345,
			'mailingList',
			MailingListStatus::get(MailingListStatus::STATUS_ENABLED),
			'description'
		);

		$mailingList->setId(54321);
		$mailingList->setName('mailingList2');
		$mailingList->setDescription('description2');

		Assert::same(54321, $mailingList->getId());
		Assert::same('mailingList2', $mailingList->getName());
		Assert::same('description2', $mailingList->getDescription());
	}
}

(new MailingListTest)->run();