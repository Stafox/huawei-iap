<?php

namespace Huawei\IAP\Request;

use GuzzleHttp\Client;
use Huawei\IAP\PurchaseData;

abstract class AbstractVerificationRequest
{
    abstract public function do();

    /** @var \GuzzleHttp\Client */
    protected $httpClient;
    /** @var PurchaseData */
    protected $purchaseData;
    /** @var array */
    protected $headers = [];

    public function __construct(Client $httpClient, PurchaseData $purchaseData)
    {
        $this->httpClient = $httpClient;
        $this->purchaseData = $purchaseData;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }
}
