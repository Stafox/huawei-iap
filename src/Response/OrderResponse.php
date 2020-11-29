<?php

namespace Huawei\IAP\Response;

/**
 * Class OrderResponse
 * Response returned in case of Order verification
 * @see https://developer.huawei.com/consumer/en/doc/development/HMS-References/iap-api-order-service-purchase-token-verification-v4
 *
 * @package Huawei\IAP\Response
 */
class OrderResponse extends ValidationResponse
{
    protected function parseDataModel(array $raw)
    {
        if (isset($raw['purchaseTokenData'])) {
            $this->dataModel = \json_decode($raw['purchaseTokenData'], true);
        }
    }

    /**
     * Consumption status, which exists only for one-off products. The options are as follows:
     * 0: not consumed
     * 1: consumed
     *
     * @return string|null
     */
    public function getConsumptionState()
    {
        return $this->dataModel['consumptionState'] ?? null;
    }
}
