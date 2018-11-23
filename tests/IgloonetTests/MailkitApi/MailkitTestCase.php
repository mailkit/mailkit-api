<?php
namespace IgloonetTests\MailkitApi;

use Tester;

class MailkitTestCase extends Tester\TestCase
{
	/**
	 * @var ClientMock
	 */
	protected $client;

	protected function setUp()
	{
		parent::setUp();

		$this->client = new ClientMock();
	}
}