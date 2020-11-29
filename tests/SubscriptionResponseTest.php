<?php

namespace Huawei\IAP\Tests;

use Huawei\IAP\Response\SubscriptionResponse;
use PHPUnit\Framework\TestCase;

class SubscriptionResponseTest extends TestCase
{

    public function testSubscriptionRawResponseParsing(): void
    {
        $jsonResponseString = \file_get_contents(__DIR__ . '/fixtures/subscription_response.json');
        $jsonResponse = \json_decode($jsonResponseString, true);

        $response = new SubscriptionResponse($jsonResponse);

        $this->assertTrue($response->isResponseValid());
        $this->assertTrue($response->isDatumExists('autoRenewing'));
        $this->assertFalse($response->isDatumExists('unexistingProperty'));
        $this->assertSame(0, $response->getPurchaseState());
        $this->assertSame(0, $response->getPurchaseType());
        $this->assertSame(3, $response->getNumOfPeriods());
        $this->assertSame(0, $response->getNumOfDiscount());
        $this->assertSame(59, $response->getDaysLasted());
        $this->assertSame('1601455139863.82EB1A71.3658', $response->getSubscriptionId());
        $this->assertSame('1601453471459.923D479D.3657', $response->getSubscriptionOriId());
        $this->assertSame('Monthly test subscription', $response->getProductName());
        $this->assertTrue($response->isSubValid());
        $this->assertTrue($response->isAutoRenewing());
        $this->assertSame('com.huawei.app', $response->getPackageName());
        $this->assertSame('com.huawei.app.01m', $response->getProductId());
        $this->assertSame('USD', $response->getCurrency());
        $this->assertSame(699, $response->getPrice());
        $this->assertSame('BY', $response->getCountry());
        $this->assertSame(1, $response->getQuantity());
        $this->assertSame('123456789', $response->getApplicationId());
        $this->assertSame(1, $response->getRenewStatus());
        $this->assertSame('SandBox_1601455450833.AA16834E.3657', $response->getPayOrderId());
        $this->assertSame('1601455136777.4820C34B.3657', $response->getLastOrderId());
        $this->assertNull($response->getExpirationIntent());
        $this->assertNull($response->getGraceExpirationTime());
        $this->assertNull($response->getCancelTime());
        $this->assertNull($response->getCancelReason());
        $this->assertNull($response->getCancellationTime());
        $this->assertNull($response->getCancelWay());
        $this->assertSame(0, $response->getTrialFlag());
        $this->assertSame(0, $response->getIntroductoryFlag());
        $this->assertSame(1, $response->getRetryFlag());
        $this->assertSame(1601457982166, $response->getExpirationDate());
        $this->assertSame(1601457682166, $response->getPurchaseTime());
        $this->assertSame(1601457082166, $response->getOriPurchaseTime());
        $this->assertSame(2, $response->getKind());
        $this->assertSame('99A8E7DBCED04A3C95C8584779F7A1E8', $response->getProductGroup());
        $this->assertSame(
            '00000174de2a2c17200178a87498e7431ca6c9ad6e4feae055593e450f46f60605903217b8963e18x4259.5.3657',
            $response->getPurchaseToken()
        );
    }
}
