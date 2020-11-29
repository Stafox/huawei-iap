<?php

namespace Huawei\IAP\Request;

use Psr\Http\Message\ResponseInterface;

class OrderVerificationRequest extends AbstractVerificationRequest
{
    const VERIFICATION_URL_MASK = '/applications/purchases/tokens/verify';

    public function do(): ResponseInterface
    {
        $url = $this->getUrl();

        $jsonBody = [
            'productId' => $this->purchaseData->getProductId(),
            'purchaseToken' => $this->purchaseData->getPurchaseToken(),
        ];

        return $this->httpClient->request('POST', $url, [
            'headers' => $this->headers,
            'json' => $jsonBody,
        ]);
    }

    protected function getUrl(): string
    {
        return self::VERIFICATION_URL_MASK;
    }
}
