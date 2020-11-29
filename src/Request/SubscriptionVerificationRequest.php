<?php

namespace Huawei\IAP\Request;

use Psr\Http\Message\ResponseInterface;

class SubscriptionVerificationRequest extends AbstractVerificationRequest
{
    const API_VERSION = 'v2';
    const VERIFICATION_URL_MASK = '/sub/applications/%s/purchases/get';

    public function do(): ResponseInterface
    {
        $url = $this->getUrl();

        $jsonBody = [
            'subscriptionId' => $this->purchaseData->getSubscriptionId(),
            'purchaseToken' => $this->purchaseData->getPurchaseToken(),
        ];

        return $this->httpClient->request('POST', $url, [
            'headers' => $this->headers,
            'json' => $jsonBody,
        ]);
    }

    protected function getUrl(): string
    {
        return sprintf(self::VERIFICATION_URL_MASK, self::API_VERSION);
    }
}
