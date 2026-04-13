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

	public const DEFAULT_API_URL = 'https://api.mailkit.eu/json.fcgi';

	public function __construct(
		private string $clientId,
		private string $clientMd5,
		private string $apiUrl = self::DEFAULT_API_URL
	) {}

	public function supportsMethod(string $method): bool
	{
		return in_array($method, self::SUPPORTED_METHODS, true);
	}

	/**
	 * @param array<string, mixed> $params
	 * @param array<string> $possibleErrors
	 */
	public function sendRequest(string $method, array $params, array $possibleErrors): IRpcResponse
	{
		[$requestData, $content, $httpStatusCode] = $this->getContent($method, $params);

		if ($content === false) {
			$lastError = error_get_last();
			throw new RpcRequestFailedException(
				$method,
				$requestData,
				sprintf('API request %s failed: %s', $method, $lastError['message'] ?? 'connection failed')
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

		if ($httpStatusCode !== null && $httpStatusCode >= 400
			&& (!isset($responseData['error_status']) || (int)$responseData['error_status'] === 0)) {
			$error = $responseData['error'] ?? $responseData['message'] ?? '';
			throw new RpcRequestFailedException(
				$method,
				$requestData,
				sprintf('API request %s failed with HTTP %d%s', $method, $httpStatusCode, $error !== '' ? ": $error" : '')
			);
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

	/** @param array<string> $possibleErrors */
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

	/**
	 * @param array<string, mixed> $params
	 * @return array<string, mixed>
	 */
	private function prepareRequestData(string $method, array $params): array
	{
		return [
			'function' => $method,
			'id' => $this->clientId,
			'md5' => $this->clientMd5,
			'parameters' => $params
		];
	}

	/**
	 * @param array<string, mixed> $data
	 * @return array<string, mixed>
	 */
	private function getStreamContextOptions(array $data): array
	{
		return [
			'http' => [
				'method' => 'POST',
				'header' => 'Content-Type: application/json',
				'content' => Json::encode($data),
				'ignore_errors' => true,
			],
			'ssl' => [
				'verify_peer' => true,
				'verify_peer_name' => true,
			],
		];
	}

	/**
	 * @param array<string, mixed> $params
	 * @return array{array<string, mixed>, string|false, int|null}
	 */
	protected function getContent(string $method, array $params): array
	{
		$requestData = $this->prepareRequestData($method, $params);

		$context = stream_context_create($this->getStreamContextOptions($requestData));

		$content = @file_get_contents($this->apiUrl, false, $context);

		$httpStatusCode = null;
		if (isset($http_response_header[0])
			&& preg_match('{^HTTP/\S+ (\d{3})}', $http_response_header[0], $m)) {
			$httpStatusCode = (int) $m[1];
		}

		return [$requestData, $content, $httpStatusCode];
	}
}
