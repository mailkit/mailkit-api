<?php

use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class MessageTest extends Tester\TestCase
{
	public function testBase()
	{
		$user = new \Igloonet\MailkitApi\DataObjects\User('example@example.cz');
		$attachment = Igloonet\MailkitApi\DataObjects\Attachment::fromString('contentAttachmentFromString', 'attachment');

		$message = new \Igloonet\MailkitApi\DataObjects\Message($user);

		$message->setSubject('example');
		$message->setTemplateVars(['example1' => 1234, 'example2' => 1234]);
		$message->setBody('exampleBody');
		$message->addAttachment($attachment);

		Assert::same($user, $message->getUser());
		Assert::same('example', $message->getSubject());
		foreach ($message->getTemplateVars() as $key => $templateVar) {
			Assert::same(1234, $templateVar);
		}
		Assert::same('exampleBody', $message->getBody());
		Assert::contains($attachment, $message->getAttachments());
	}
}

(new MessageTest)->run();