<?php

namespace Huawei\IAP;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;
use Huawei\IAP\Exception\InvalidValidationResponseException;
use Huawei\IAP\Request\AbstractVerificationRequest;
use Huawei\IAP\Request\OrderVerificationRequest;
use Huawei\IAP\Request\SubscriptionVerificationRequest;
use Huawei\IAP\Response\OrderResponse;
use Huawei\IAP\Response\SubscriptionResponse;

class Validator
{
    const TYPE_SUBSCRIPTION = 'subscription';
    const TYPE_ORDER = 'order';

    /** @var \Huawei\IAP\AuthorizationStorage */
    protected $authorizationStorage;
    /** @var \Huawei\IAP\StoreSiteSelector */
    protected $siteSelector;

    protected function getClientConfig()
    {
        return ['timeout' => 5];
    }

    public function __construct()
    {
        $this->authorizationStorage = new AuthorizationStorage();
        $this->siteSelector = new StoreSiteSelector();
    }

    /**
     * @param AuthorizationStorage $storage
     */
    public function setAuthorizationStorage(AuthorizationStorage $storage)
    {
        $this->authorizationStorage = $storage;
    }

    protected function getClient(string $type, ?string $country = null, ?int $accountFlag = null)
    {
        $choice = $this->siteSelector->selectSite($type, $country, $accountFlag);
        $baseUrl = $choice->getSiteUrl();

        $config = array_merge(['base_uri' => $baseUrl], $this->getClientConfig());

        return new Client($config);
    }

    protected function getAuthorizationBuilder()
    {
        return new AuthorizationBuilder();
    }

    /**
     * @param AuthorizationCredentials $credentials
     * @param PurchaseData $purchaseData
     * @return OrderResponse|SubscriptionResponse
     */
    public function validate(AuthorizationCredentials $credentials, PurchaseData $purchaseData)
    {
        try {
            $request = $this->makeRequest($purchaseData, $credentials);
            $response = $request->do();
        } catch (BadResponseException $ex) {
            $errorConnectedWithAccess = $ex->getCode() === 401;
            $request = $this->makeRequest($purchaseData, $credentials, $errorConnectedWithAccess);
            $response = $request->do();
        }

        return $this->wrapResponse($response, $request);
    }

    /**
     * @param PurchaseData $purchaseData
     * @return SubscriptionVerificationRequest
     */
    protected function createSubscriptionVerificationRequest(PurchaseData $purchaseData): SubscriptionVerificationRequest
    {
        $type = $purchaseData->getType();
        $client = $this->getClient($type);

        return new SubscriptionVerificationRequest($client, $purchaseData);
    }

    /**
     * @param PurchaseData $purchaseData
     * @return OrderVerificationRequest
     */
    protected function createOrderVerificationRequest(PurchaseData $purchaseData): OrderVerificationRequest
    {
        $type = $purchaseData->getType();
        $client = $this->getClient($type);

        return new OrderVerificationRequest($client, $purchaseData);
    }

    /**
     * @param PurchaseData             $purchaseData
     * @param AuthorizationCredentials $credentials
     * @param bool                     $forceAuthorization
     *
     * @return AbstractVerificationRequest
     */
    protected function makeRequest(
        PurchaseData $purchaseData,
        AuthorizationCredentials $credentials,
        bool $forceAuthorization = false
    ): AbstractVerificationRequest
    {
        $type = $purchaseData->getType();

        if ($type === self::TYPE_SUBSCRIPTION) {
            $request = $this->createSubscriptionVerificationRequest($purchaseData);
        } elseif ($type === self::TYPE_ORDER) {
            $request = $this->createOrderVerificationRequest($purchaseData);
        } else {
            throw new \RuntimeException('Unsupported purchase type for verification');
        }

        $headers = [
            'Authorization' => $this->buildAuthHeader($credentials, $forceAuthorization),
        ];

        $request->setHeaders($headers);

        return $request;
    }

    protected function buildAuthHeader(
        AuthorizationCredentials $credentials,
        bool $forceAuthorization
    ): string
    {
        $authorizationBuilder = $this->getAuthorizationBuilder();

        $accessToken = $this->authorizationStorage->fetch($credentials);

        if ($forceAuthorization || $accessToken === null) {
            $accessToken = $authorizationBuilder->getAccessToken($credentials);
            $this->authorizationStorage->save($credentials, $accessToken);
        }

        return $authorizationBuilder->buildAuthorizationHeader($accessToken);
    }

    protected function wrapResponse(
        ResponseInterface $response,
        AbstractVerificationRequest $request
    )
    {
        $body = $response->getBody()->getContents();
        $json = \json_decode($body, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidValidationResponseException();
        }

        if ($request instanceof SubscriptionVerificationRequest) {
            return new SubscriptionResponse($json);
        }

        if ($request instanceof OrderVerificationRequest) {
            return new OrderResponse($json);
        }

        throw new \RuntimeException('Unsupported verification request');
    }

}
