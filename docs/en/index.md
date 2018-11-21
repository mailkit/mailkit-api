## Installation

You can install the extension using this command

```sh
$ composer require igloonet/mailkit-api
```

## Usage

```php
<?php 

// if you're using composer
require_once __DIR__ . '/vendor/autoload.php';


$clientId = 'id';
$clientMd5 = 'md5';
$client = new \Igloonet\MailkitApi\RPC\Client($clientId, $clientMd5);

$userManager = new \Igloonet\MailkitApi\Managers\UsersManager($client, ['cs'], 'cs');
$messagesManager = new \Igloonet\MailkitApi\Managers\MessagesManager($client, ['cs'], 'cs');
$listManager = new \Igloonet\MailkitApi\Managers\MailingListsManager($client, ['cs'], 'cs');

// get users
$users = $userManager->getUsersByEmailAddress('example@example.cz');
$user = $userManager->getUserByEmailId(1234);

// create mailing list
$mailingList = $listManager->getMailingListByName('exampleMailingList');

// add user to mailingList
$user = new \Igloonet\MailkitApi\DataObjects\User('example1@example.cz');
$newUser = $userManager->addUser($user, $mailingList->getId(), false);

// send email
$message = new \Igloonet\MailkitApi\DataObjects\Message($user);
$message->setBody('test messages');
$campaignId = 11111;
$result = $messagesManager->sendMail($message, $mailingList->getId(), $campaignId);

var_dump($result);

//WebHook manager 
$webHookManager = new \Igloonet\MailkitApi\Managers\WebHooksManager();

//WebHook subscribe
$jsonPayloadFromWebHook = '{EMAIL":"example@example.cz", "ID_EMAIL":"1", .......... }'; //Webhook payload
$subscribeValueObject = $webHookManager->processSubscribe($jsonPayloadFromWebHook);

//WebHook unsubscribe
$jsonPayloadFromWebHook = '{EMAIL":"example@example.cz", "ID_EMAIL":"1", .......... }'; //Webhook payload
$unsubscribeValueObject = $webHookManager->processUnsubscribe($jsonPayloadFromWebHook);
```
