<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\Managers;

use Igloonet\MailkitApi\DataObjects\User;
use Igloonet\MailkitApi\Exceptions\UnsupportedLanguageException;
use Igloonet\MailkitApi\RPC\Client;
use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;

abstract class BaseManager
{
	public const LANGUAGE_DEFAULT = 'default';

	/** @var Client */
	protected $client = null;

	/** @var array|string[] */
	protected $enabledLanguages = null;

	/** @var string */
	protected $defaultLanguage = null;

	public function __construct(Client $client, array $enabledLanguages, string $defaultLanguage)
	{
		$this->client = $client;
		$this->enabledLanguages = $enabledLanguages;
		$this->defaultLanguage = $this->validateLanguage($defaultLanguage);
	}

	protected function sendRpcRequest(string $method, array $params, array $possibleErrors): IRpcResponse
	{
		return $this->client->sendRpcRequest($method, $params, $possibleErrors);
	}

	protected function getBooleanString(bool $value): string
	{
		return $value === true ? "TRUE" : "FALSE";
	}

	protected function encodeString(?string $str = null): ?string
	{
		return $str === null ? null : base64_encode($str);
	}

	protected function filterNullsFromArray(array $arr): array
	{
		return array_filter($arr, function ($value) {
			return $value !== null;
		});
	}

	protected function validateLanguage(?string $language): ?string
	{
		if ($language === null) {
			return null;
		} elseif ($language === self::LANGUAGE_DEFAULT) {
			return $this->defaultLanguage;
		}

		$language = trim(strtolower($language));

		if (!in_array($language, $this->enabledLanguages, true)) {
			throw new UnsupportedLanguageException($language);
		}

		return $language;
	}

	protected function getUserDataSections(User $user, ?string $returnUrl, ?string $templateId): array
	{
		$data1 = [
			'ID_template' => $this->encodeString($templateId),
			'return_url' => $this->encodeString($returnUrl),
			'vocative' => $this->encodeString($user->getVocative()),
			'prefix' => $this->encodeString($user->getPrefix()),
			'first_name' => $this->encodeString($user->getFirstName()),
			'last_name' => $this->encodeString($user->getLastName()),
			'status' => $this->encodeString($user->getStatus()?->value),
			'email' => $this->encodeString($user->getEmail()),
			'reply_to' => $this->encodeString($user->getReplyTo()),
			'company' => $this->encodeString($user->getCompany()),
		];

		$data2 = [
			'nick_name' => $this->encodeString($user->getNickName()),
			'country' => $this->encodeString($user->getCountry()),
			'street' => $this->encodeString($user->getStreet()),
			'state' => $this->encodeString($user->getState()),
			'zip' => $this->encodeString($user->getZip()),
			'city' => $this->encodeString($user->getCity()),
			'mobile' => $this->encodeString($user->getMobile()),
			'phone' => $this->encodeString($user->getPhone()),
			'fax' => $this->encodeString($user->getFax()),
			'gender' => $this->encodeString($user->getGender()?->value),
		];

		$data3 = [];
		foreach ($user->getCustomFields() as $fieldNumber => $value) {
			$data3['custom'.$fieldNumber] = $this->encodeString($value);
		}

		$data1 = $this->filterNullsFromArray($data1);
		$data2 = $this->filterNullsFromArray($data2);
		$data3 = $this->filterNullsFromArray($data3);

		return [$data1, $data2, $data3];
	}
}
