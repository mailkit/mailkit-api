# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Install dependencies
composer install

# Run tests
vendor/bin/tester -c ./tests/php.ini-unix ./tests/IgloonetTests/

# Run a single test file
vendor/bin/tester -c ./tests/php.ini-unix ./tests/IgloonetTests/SomeTest.phpt

# Run static analysis (PHPStan level 6)
vendor/bin/phpstan analyse -c build/phpstan.neon -l 6 src tests

# Run tests with coverage (using phpdbg)
phpdbg -qrr vendor/bin/tester -c ./tests/php.ini-unix --coverage ./tests/tmp/coverage.xml --coverage-src ./src ./tests/IgloonetTests/
```

## Architecture

This is a PHP client library (`igloonet/mailkit-api`) for the Mailkit email service API. All files use `declare(strict_types=1)`.

**Entry point:** `MailkitApi` — a facade that composes four managers injected via constructor.

**Layer overview:**

1. **DataObjects** (`src/DataObjects/`) — Value objects with fluent setters: `User`, `Message`, `Attachment`, `MailingList`, webhooks. Enums in `DataObjects/Enums/` are native PHP 8.1 backed enums.

2. **Managers** (`src/Managers/`) — Business logic. All extend `BaseManager` which provides RPC client access, language validation, base64 encoding, and utilities like `getUserDataSections()` (splits user data into 3 arrays as required by the Mailkit API).

3. **RPC layer** (`src/RPC/`) — `Client` dispatches to protocol adapters. `XmlAdapter` is the active implementation; it POSTs XML-RPC requests to `https://api.mailkit.eu/rpc.fcgi`. `JsonAdapter` is a stub for future use. Responses are parsed into typed `IRpcResponse` objects.

4. **Exceptions** (`src/Exceptions/`) — ~60 specific exception classes organized by subsystem (User/, MailingList/, Message/, EventApi/), all implementing the `MailkitApiException` marker interface.

**Request flow (e.g., sending an email):**
`MessagesManager::sendMail()` → encodes data (base64) → splits into sections → `Client::sendRpcRequest()` → `XmlAdapter::sendRequest()` → parses XML response → returns `SendMailResult`.

## Testing

Tests use **Nette Tester** (`.phpt` format). Bootstrap at `tests/IgloonetTests/bootstrap.php` sets timezone to `Europe/Prague` and loads the autoloader. Mock XML/JSON fixtures are in `tests/IgloonetTests/mock-files/`.
