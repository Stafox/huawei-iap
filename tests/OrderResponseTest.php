<?php

namespace Huawei\IAP\Tests;

use Huawei\IAP\Response\OrderResponse;
use PHPUnit\Framework\TestCase;

class OrderResponseTest extends TestCase
{

    public function testOrderRawResponseParsing(): void
    {
        $jsonResponseString = \file_get_contents(__DIR__ . '/fixtures/order_response.json');
        $jsonResponse = \json_decode($jsonResponseString, true);

        $response = new OrderResponse($jsonResponse);
        $this->assertTrue(is_array($response->getDataModel()));
        $this->assertSame(0, $response->getResponseCode());
        $this->assertNull($response->getResponseMessage());
        $this->assertNull($response->getDeveloperChallenge());
        $this->assertNull($response->getDeveloperPayload());
        $this->assertNull($response->getAccountFlag());
        $this->assertTrue($response->isResponseValid());
        $this->assertTrue($response->isDatumExists('autoRenewing'));
        $this->assertFalse($response->getDatum('autoRenewing'));
        $this->assertFalse($response->isDatumExists('unexistingProperty'));
        $this->assertSame(0, $response->getPurchaseState());
        $this->assertSame(0, $response->getPurchaseType());
        $this->assertFalse($response->isAutoRenewing());
        $this->assertSame('1601455440833.AA16834E.3657', $response->getOrderId());
        $this->assertSame('com.huawei.app', $response->getPackageName());
        $this->assertSame('com.huawei.app.purchase', $response->getProductId());
        $this->assertSame('USD', $response->getCurrency());
        $this->assertSame('Test one-time', $response->getProductName());
        $this->assertSame('0', $response->getPayType());
        $this->assertSame(699, $response->getPrice());
        $this->assertSame('BY', $response->getCountry());
        $this->assertSame(1, $response->getQuantity());
        $this->assertSame('123456789', $response->getApplicationId());
        $this->assertSame('SandBox_1601455450833.AA16834E.3657', $response->getPayOrderId());
        $this->assertSame(1601457682166, $response->getPurchaseTime());
        $this->assertSame(1, $response->getConfirmed());
        $this->assertSame(1, $response->getKind());
        $this->assertSame('99A8E7DBCED04A3C95C8584779F7A1E8', $response->getProductGroup());
        $this->assertSame(
            '00000174de2a2c17200178a87498e7431ca6c9ad6e4feae055593e450f46f60605903217b8963e18x4259.5.3657',
            $response->getPurchaseToken()
        );
        $this->assertSame(0, $response->getConsumptionState());
    }
}
