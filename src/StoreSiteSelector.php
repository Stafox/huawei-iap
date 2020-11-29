<?php


namespace Huawei\IAP;

/**
 * Class StoreSiteSelector
 *
 * Allows select Store site which being used for verification.
 *
 * @package Huawei\IAP
 */
class StoreSiteSelector
{
    const CHINA = 'CN';
    const GERMANY = 'DE';
    const SINGAPORE = 'SG';
    const RUSSIA = 'RU';

    /** @var int
     * Account type. The options are as follows:
     * 1: AppTouch user account
     * Other values: HUAWEI ID
     *
     * @see https://developer.huawei.com/consumer/en/doc/development/HMS-References/iap-InAppPurchaseData#getAccountFlag
     */
    const ACCOUNT_FLAG = 1;

    protected $defaultOrderCountry = self::GERMANY;
    protected $defaultSubscriptionCountry = self::GERMANY;

    protected $appTouchOrderSiteMapping = [
        self::GERMANY => 'https://orders-at-dre.iap.dbankcloud.com',
    ];

    protected $appTouchSubscriptionSiteMapping = [
        self::GERMANY => 'https://subscr-at-dre.iap.dbankcloud.com',
    ];

    protected $orderSiteMapping = [
        self::CHINA => 'https://orders-drcn.iap.hicloud.com',
        self::GERMANY => 'https://orders-dre.iap.hicloud.com',
        self::SINGAPORE => 'https://orders-dra.iap.hicloud.com',
        self::RUSSIA => 'https://orders-drru.iap.hicloud.com',
    ];

    protected $subscriptionSiteMapping = [
        self::CHINA => 'https://subscr-drcn.iap.hicloud.com',
        self::GERMANY => 'https://subscr-dre.iap.hicloud.com',
        self::SINGAPORE => 'https://subscr-dra.iap.hicloud.com',
        self::RUSSIA => 'https://subscr-drru.iap.hicloud.com',
    ];

    /**
     * @param string $type $purchaseType ['order', 'subscription']
     * @param string|null $country - Desired country
     * @param int|null $accountFlag - If `1` app touch site will be used
     *
     * @see https://developer.huawei.com/consumer/en/doc/development/HMS-References/iap-api-specification-related-v4
     *
     * @throws \RuntimeException
     *
     * @return StoreSiteSelectorChoice
     */
    public function selectSite(
        string $type,
        ?string $country,
        ?int $accountFlag
    ): StoreSiteSelectorChoice
    {
        $appTouchSite = $accountFlag === self::ACCOUNT_FLAG;

        if ($type === Validator::TYPE_ORDER && $appTouchSite) {
            if ($country !== null && isset($this->appTouchOrderSiteMapping[$country])) {
                return new StoreSiteSelectorChoice(
                    $country,
                    $this->appTouchOrderSiteMapping[$country],
                    $appTouchSite
                );
            }

            return new StoreSiteSelectorChoice(
                self::GERMANY,
                $this->appTouchOrderSiteMapping[self::GERMANY],
                $appTouchSite
            );
        }

        if ($type === Validator::TYPE_ORDER) {
            if ($country !== null && isset($this->orderSiteMapping[$country])) {
                return new StoreSiteSelectorChoice(
                    $country,
                    $this->orderSiteMapping[$country],
                    $appTouchSite
                );
            }

            return new StoreSiteSelectorChoice(
                $this->defaultOrderCountry,
                $this->orderSiteMapping[$this->defaultOrderCountry],
                $appTouchSite
            );
        }

        if ($type === Validator::TYPE_SUBSCRIPTION && $appTouchSite) {
            if ($country !== null && isset($this->appTouchSubscriptionSiteMapping[$country])) {
                return new StoreSiteSelectorChoice(
                    $country,
                    $this->appTouchSubscriptionSiteMapping[$country],
                    $appTouchSite
                );
            }

            return new StoreSiteSelectorChoice(
                self::GERMANY,
                $this->appTouchSubscriptionSiteMapping[self::GERMANY],
                $appTouchSite
            );
        }

        if ($type === Validator::TYPE_SUBSCRIPTION) {
            if ($country !== null && isset($this->subscriptionSiteMapping[$country])) {
                return new StoreSiteSelectorChoice(
                    $country,
                    $this->subscriptionSiteMapping[$country],
                    $appTouchSite
                );
            }

            return new StoreSiteSelectorChoice(
                $this->defaultSubscriptionCountry,
                $this->subscriptionSiteMapping[$this->defaultSubscriptionCountry],
                $appTouchSite
            );
        }

        throw new \RuntimeException(\sprintf(
            'Can not select site using (%s)',
            \json_encode(['type' => $type, 'country' => $country, 'accountFlag' => $accountFlag])
        ));
    }

}
