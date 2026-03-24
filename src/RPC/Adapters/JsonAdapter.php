<?php
declare(strict_types=1);

namespace Igloonet\MailkitApi\RPC\Adapters;

use Igloonet\MailkitApi\RPC\Exceptions\InvalidRpcResponseDataTypeException;
use Igloonet\MailkitApi\RPC\Exceptions\InvalidRpcResponseException;
use Igloonet\MailkitApi\RPC\Exceptions\RpcRequestFailedException;
use Igloonet\MailkitApi\RPC\Exceptions\RpcResponseUnknownErrorException;
use Igloonet\MailkitApi\RPC\Exceptions\UnauthorizedException;
use Igloonet\MailkitApi\RPC\Responses\IRpcResponse;
use Igloonet\MailkitApi\RPC\Responses\JsonErrorRpcResponse;
use Igloonet\MailkitApi\RPC\Responses\JsonSuccessRpcResponse;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class JsonAdapter implements IAdapter
{
	public const SUPPORTED_METHODS = [
		'mailkit.mailinglist.list',
		'mailkit.mailinglist.create',
		'mailkit.mailinglist.delete',
		'mailkit.mailinglist.import',
		'mailkit.mailinglist.getstatus',
		'mailkit.mailinglist.unsubscribed',
		'mailkit.mailinglist.adduser',
		'mailkit.mailinglist.edituser',
		'mailkit.email.getstatus',
		'mailkit.email.getstatus.history',
		'mailkit.email.unsubscribe',
		'mailkit.email.unsubscribe.getstatus',
		'mailkit.email.move',
		'mailkit.email.delete',
		'mailkit.email.revalidate',
		'mailkit.campaigns.history',
		'mailkit.report',
		'mailkit.report.campaign',
		'mailkit.report.message',
		'mailkit.report.message.recipients',
		'mailkit.report.message.feedback',
		'mailkit.report.message.links',
		'mailkit.report.message.links.visitors',
		'mailkit.report.message.bounces',
		'mailkit.report.raw.messages',
		'mailkit.report.raw.responses',
		'mailkit.report.raw.bounces',
		'mailkit.sendmail',
	];

	private string $apiUrl = 'https://api.mailkit.eu/json.fcgi';

	public function __construct(
		private string $clientId,
		private string $clientMd5
	) {}

	public function supportsMethod(string $method): bool
	{
		return in_array($method, self::SUPPORTED_METHODS, true);
	}

	public function sendRequest(string $method, array $params, array $possibleErrors): IRpcResponse
	{
		[$requestData, $content] = $this->getContent($method, $params);

		if ($content === false) {
			throw new RpcRequestFailedException(
				$method,
				$requestData
			);
		}

		try {
			$responseData = Json::decode($content, Json::FORCE_ARRAY);
		} catch (JsonException $ex) {
			throw new InvalidRpcResponseException(
				$method,
				$requestData,
				$content,
				sprintf('Unable to decode JSON API response for method %s', $method),
				0,
				$ex
			);
		}

		if (!is_array($responseData)) {
			throw new InvalidRpcResponseDataTypeException($method, $requestData, $responseData);
		}

		if (isset($responseData['error_status']) && (int)$responseData['error_status'] !== 0) {
			$error = $responseData['error'] ?? '';
			if (trim($error) === 'Unauthorized') {
				throw new UnauthorizedException($method, $requestData, '', (int)$responseData['error_status']);
			} elseif (str_starts_with(trim($error), 'Disallowed IP')) {
				throw new UnauthorizedException($method, $requestData, $error, (int)$responseData['error_status']);
			} elseif (!$this->isKnownError($error, $possibleErrors)) {
				throw new RpcResponseUnknownErrorException(
					$method,
					$requestData,
					$error,
					$possibleErrors,
					'',
					$responseData['error_status']
				);
			}

			return new JsonErrorRpcResponse($error, (int)$responseData['error_status']);
		}

		return new JsonSuccessRpcResponse($responseData);
	}

	private function isKnownError(string $error, array $possibleErrors): bool
	{
		foreach ($possibleErrors as $possibleError) {
			if ($error === $possibleError) {
				return true;
			}
			// Only try regex match if the string starts with a valid PCRE delimiter
			// (non-alphanumeric, non-backslash, non-NUL) to avoid E_WARNING on plain strings
			if (preg_match('{^[^a-zA-Z0-9\\\\\x00].}', $possibleError)
				&& preg_match($possibleError, $error) === 1) {
				return true;
			}
		}
		return false;
	}

	private function prepareRequestData(string $method, array $params): array
	{
		return [
			'function' => $method,
			'id' => $this->clientId,
			'md5' => $this->clientMd5,
			'parameters' => $params
		];
	}

	private function getStreamContextOptions(array $data): array
	{
		return [
			'http' => [
				'method' => 'POST',
				'header' => 'Content-Type: application/json',
				'content' => Json::encode($data)
			],
			'ssl' => [
				'verify_peer' => true,
				'verify_peer_name' => true,
			],
		];
	}

	protected function getContent(string $method, array $params): array
	{
		$requestData = $this->prepareRequestData($method, $params);

		$context = stream_context_create($this->getStreamContextOptions($requestData));

		$content = file_get_contents($this->apiUrl, false, $context);

		return [$requestData, $content];
	}
}
