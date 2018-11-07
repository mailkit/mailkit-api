<?php

use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class AttachmentTest extends Tester\TestCase
{
	public function testAttachmentFromString()
	{
		$attachment = Igloonet\MailkitApi\DataObjects\Attachment::fromString('contentAttachmentFromString', 'attachment');

		Assert::same('attachment', $attachment->getName());
		Assert::same('contentAttachmentFromString', $attachment->getContent());
	}

	public function testAttachmentFromFile()
	{
		$attachment = Igloonet\MailkitApi\DataObjects\Attachment::fromFile(__DIR__ .'/../mock-files/fromFile.txt', 'attachment');

		Assert::same('attachment', $attachment->getName());
		Assert::same('contentAttachmentFromFile', $attachment->getContent());
	}

	public function testAttachmentFromFileNotFoundException()
	{
		$attachment = Igloonet\MailkitApi\DataObjects\Attachment::fromFile('', 'attachment');

		Assert::exception(function() use ($attachment) {
			$attachment->getContent();
		}, Igloonet\MailkitApi\Exceptions\Message\AttachmentFileNotFoundException::class);
	}
}

(new AttachmentTest)->run();