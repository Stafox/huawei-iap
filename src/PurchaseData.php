<?php

namespace Huawei\IAP;

/**
 * Class PurchaseData
 *
 * VO for IAP data
 *
 * @package Huawei\IAP
 */
class PurchaseData
{
    private $type;
    private $subscriptionId;
    private $purchaseToken;
    private $productId;

    /**
     * PurchaseData constructor.
     * @param string $type
     * @param string|null $subscriptionId
     * @param string $purchaseToken
     * @param string $productId
     */
    public function __construct(
        string $type,
        ?string $subscriptionId,
        string $purchaseToken,
        string $productId
    )
    {
        $this->type = $type;
        $this->subscriptionId = $subscriptionId;
        $this->purchaseToken = $purchaseToken;
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * SubscriptionId is null for in-app purchase
     *
     * @return string|null
     */
    public function getSubscriptionId(): ?string
    {
        return $this->subscriptionId;
    }

    /**
     * @return string
     */
    public function getPurchaseToken(): string
    {
        return $this->purchaseToken;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }
}
