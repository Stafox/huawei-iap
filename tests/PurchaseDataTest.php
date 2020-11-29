<?php

namespace Huawei\IAP\Tests;

use Huawei\IAP\PurchaseData;
use PHPUnit\Framework\TestCase;

class PurchaseDataTest extends TestCase
{
    public function testAuthorizationCredentials(): void
    {
        $data = new PurchaseData(
            'order', 'testSubId', 'testPurchaseToken', 'testProductId'
        );
        $this->assertSame('order', $data->getType());
        $this->assertSame('testSubId', $data->getSubscriptionId());
        $this->assertSame('testPurchaseToken', $data->getPurchaseToken());
        $this->assertSame('testProductId', $data->getProductId());
    }
}
