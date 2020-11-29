<?php

namespace Huawei\IAP\Tests;

use Huawei\IAP\StoreSiteSelector;
use PHPUnit\Framework\TestCase;

class StoreSiteSelectorTest extends TestCase
{
    public function testStoreSelectorThrownException(): void
    {
        $this->expectException(\RuntimeException::class);
        $selector = new StoreSiteSelector();
        $selector->selectSite('unknownType', null, null);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $type
     * @param string|null $country
     * @param int|null $accountFlag
     * @param string $expectedCountry
     * @param bool $expectedAppTouchSite
     * @param string $expectedSiteUrl
     */
    public function testStoreSelector(
        string $type,
        ?string $country,
        ?int $accountFlag,
        string $expectedCountry,
        bool $expectedAppTouchSite,
        string $expectedSiteUrl
    ): void
    {
        $selector = new StoreSiteSelector();
        $choice = $selector->selectSite($type, $country, $accountFlag);
        $this->assertSame($expectedCountry, $choice->getCountry());
        $this->assertSame($expectedAppTouchSite, $choice->isAppTouchSite());
        $this->assertSame($expectedSiteUrl, $choice->getSiteUrl());
    }

    public function dataProvider(): array
    {
        return [
            [
                'order', 'DE', null, 'DE', false, 'https://orders-dre.iap.hicloud.com'
            ],
            [
                'order', null, null, 'DE', false, 'https://orders-dre.iap.hicloud.com'
            ],
            [
                'order', 'BY', null, 'DE', false, 'https://orders-dre.iap.hicloud.com'
            ],
            [
                'order', 'DE', 1, 'DE', true, 'https://orders-at-dre.iap.dbankcloud.com'
            ],
            [
                'order', null, 1, 'DE', true, 'https://orders-at-dre.iap.dbankcloud.com'
            ],
            [
                'order', 'RU', 1, 'DE', true, 'https://orders-at-dre.iap.dbankcloud.com'
            ],
            [
                'order', 'RU', null, 'RU', false, 'https://orders-drru.iap.hicloud.com'
            ],
            [
                'order', 'CN', null, 'CN', false, 'https://orders-drcn.iap.hicloud.com'
            ],
            [
                'order', 'SG', null, 'SG', false, 'https://orders-dra.iap.hicloud.com'
            ],
            [
                'subscription', 'RU', 1, 'DE', true, 'https://subscr-at-dre.iap.dbankcloud.com'
            ],
            [
                'subscription', 'DE', 1, 'DE', true, 'https://subscr-at-dre.iap.dbankcloud.com'
            ],
            [
                'subscription', null, 1, 'DE', true, 'https://subscr-at-dre.iap.dbankcloud.com'
            ],
            [
                'subscription', 'DE', null, 'DE', false, 'https://subscr-dre.iap.hicloud.com'
            ],
            [
                'subscription', null, null, 'DE', false, 'https://subscr-dre.iap.hicloud.com'
            ],
            [
                'subscription', 'BY', null, 'DE', false, 'https://subscr-dre.iap.hicloud.com'
            ],
            [
                'subscription', 'RU', null, 'RU', false, 'https://subscr-drru.iap.hicloud.com'
            ],
            [
                'subscription', 'CN', null, 'CN', false, 'https://subscr-drcn.iap.hicloud.com'
            ],
            [
                'subscription', 'SG', null, 'SG', false, 'https://subscr-dra.iap.hicloud.com'
            ]
        ];
    }
}
