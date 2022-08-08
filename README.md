Huawei IAP
=======

[![Latest Stable Version](https://poser.pugx.org/stafox/huawei-iap/v)](//packagist.org/packages/stafox/huawei-iap) 
[![Total Downloads](https://poser.pugx.org/stafox/huawei-iap/downloads)](//packagist.org/packages/stafox/huawei-iap)
[![Build Status](https://travis-ci.org/stafox/huawei-iap.png?branch=main)](https://travis-ci.org/stafox/huawei-iap)
[![Code Coverage](https://scrutinizer-ci.com/g/Stafox/huawei-iap/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/stafox/huawei-iap/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/stafox/huawei-iap/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/stafox/huawei-iap/?branch=main)
[![License](https://poser.pugx.org/stafox/huawei-iap/license)](//packagist.org/packages/stafox/huawei-iap)

PHP library that can be used for verifying In-App Purchases for the Huawei's Order and Subscription services.

## Requirements

* PHP >= 7.1
* ext-json
* ext-curl

## Getting Started

The easiest way to work with this package is when it's installed as a
Composer package inside your project. Composer isn't strictly
required, but makes life a lot easier.

If you're not familiar with Composer, please see <http://getcomposer.org/>.

1. Add `huawei-iap` to your application's composer.json.

        {
            ...
            "require": {
                "stafox/huawei-iap": "main"
            },
            ...
        }

2. Run `php composer install`.

3. If you haven't already, add the Composer autoload to your project's
   initialization file. (example)

        require 'vendor/autoload.php';


## Quick Usage Examples ##

### Subscription validation

```php

use Huawei\IAP\AuthorizationCredentials;
use Huawei\IAP\Validator as HuaweiValidator;

$validator = new HuaweiValidator();

$appId = 123456789; // Your application ID
$appKey = 'XXXYYYZZZ'; // Your app key

$authCredentials = new AuthorizationCredentials($appId, $appKey);

$type = HuaweiValidator::TYPE_SUBSCRIPTION;
$subscriptionId = 'AAABBBCCC';
$productId = 'com.your.app.subscription';
$purchaseToken = 'purchaseTokenHere';

$purchaseData = new PurchaseData($type, $subscriptionId, $purchaseToken, $productId);

$subscriptionResponse = $validator->validate($authCredentials, $purchaseData);

$isSubscriptionValid = $subscriptionResponse->isSubValid();
$expirationDateMs = $subscriptionResponse->getExpirationDate();
```

### Order (one-time purchase) validation

```php

use Huawei\IAP\AuthorizationCredentials;
use Huawei\IAP\Validator as HuaweiValidator;

$validator = new HuaweiValidator();

$appId = 123456789; // Your application ID
$appKey = 'XXXYYYZZZ'; // Your app key

$authCredentials = new AuthorizationCredentials($appId, $appKey);

$type = HuaweiValidator::TYPE_ORDER;
$productId = 'com.your.app.subscription';
$purchaseToken = 'purchaseTokenHere';

$purchaseData = new PurchaseData($type, null, $purchaseToken, $productId);

$orderResponse = $validator->validate($authCredentials, $purchaseData);

$pruchaseKind = $orderResponse->getKind();
$orderId = $orderResponse->getOrderId();
$consumptionState = $orderResponse->getConsumptionState();
```

### Use custom AuthorizationStorage

By default auth token stored in-memory. To reduce number of authorization requests you can extend AuthorizationStorage
to store data for longer period of time.

For example:

```php
use Huawei\IAP\AuthorizationStorage;
use Redis;

class RedisAuthorizationStorage extends AuthorizationStorage
{
    private $redisClient;

    public function __construct(Redis $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public function fetch(AuthorizationCredentials $credentials): ?string
    {
        $key = $this->transformCredentialsToKey($credentials);

        $at = $this->redisClient->get($key);
        return $at === false ? null : $at;
    }

    public function save(AuthorizationCredentials $credentials, string $accessToken): void
    {
        $key = $this->transformCredentialsToKey($credentials);

        $this->redisClient->set($key, $accessToken);
    }
}
```

And then pass it into Validator.

```php
use Huawei\IAP\Validator as HuaweiValidator;


$redisAuthStorage = new RedisAuthorizationStorage($redisClient);

$validator = new HuaweiValidator();
$validator->setAuthorizationStorage($redisAuthStorage);
```

### Select store site

By default Germany store site will be used. It may be useful to use different store sites to reduce request time.

To do that you need to extend `Validator` and override
* `createSubscriptionVerificationRequest(PurchaseData $purchaseData)`
* `createOrderVerificationRequest(PurchaseData $purchaseData)`

And extend `PurchaseDate` to be able get country, for example.

Then pass needed country to `Validator::getClient()` method.
