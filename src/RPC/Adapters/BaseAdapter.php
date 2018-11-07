<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Adapters;

abstract class BaseAdapter implements IAdapter
{
	/** @var string */
	protected $clientId = null;

	/** @var string  */
	protected $clientMd5 = null;

	public function __construct(string $clientId, string $clientMd5)
	{
		$this->clientId = $clientId;
		$this->clientMd5 = $clientMd5;
	}
}
