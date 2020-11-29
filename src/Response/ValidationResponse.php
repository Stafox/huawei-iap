<?php

namespace Huawei\IAP\Response;

abstract class ValidationResponse
{
    /**
     * @var int
     * Success
     */
    const RESPONSE_CODE_0 = 0;
    /**
     * @var int
     * The parameter passed to the API is invalid.
     * This error may also indicate that an agreement is not signed
     * or parameters are not set correctly for the in-app purchase
     * settlement in HUAWEI IAP, or the required permission is not in the list.
     *
     * Solution: Check whether the parameter passed to the API is correctly set.
     * If so, check whether required settings in HUAWEI IAP are correctly configured.
     */
    const RESPONSE_CODE_5 = 5;

    /**
     * @var int
     * A critical error occurs during API operations.
     *
     * Solution: Rectify the fault based on the error information in the response.
     * If the fault persists, contact Huawei technical support.
     */
    const RESPONSE_CODE_6 = 6;
    /**
     * @var int
     * A user failed to consume or confirm a product because the user does not own the product.
     */
    const RESPONSE_CODE_8 = 8;
    /**
     * @var int
     * The product cannot be consumed or confirmed because it has been consumed or confirmed.
     */
    const RESPONSE_CODE_9 = 9;
    /**
     * @var int
     * The user account is abnormal, for example, the user has been deregistered.
     */
    const RESPONSE_CODE_11 = 11;
    /**
     * @var int
     * The order does not exist. Only the latest order of the specified product can be queried.
     * The order in this query may not the latest one.
     *
     * Solution: Token verification is not required for historical orders.
     * Make sure that your integration is performed based on the guide.
     * If the problem persists, contact Huawei technical support.
     */
    const RESPONSE_CODE_12 = 12;

    protected $dataModel = [];
    protected $responseCode;
    protected $responseMessage;

    public function __construct(array $raw)
    {
        $this->parseDataModel($raw);
        $this->parseResonseDetails($raw);
    }

    abstract protected function parseDataModel(array $raw);

    protected function parseResonseDetails(array $raw)
    {
        $responseCode = $raw['responseCode'] ?? null;

        if ($responseCode !== null) {
            $this->responseCode = (int)$responseCode;
        }

        $responseMessage = $raw['responseMessage'] ?? null;

        if ($responseMessage !== null) {
            $this->responseMessage = (string)$responseMessage;
        }
    }

    public function getResponseMessage(): ?string
    {
        return $this->responseMessage;
    }

    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }

    public function isResponseValid(): bool
    {
        return $this->responseCode === self::RESPONSE_CODE_0;
    }

    /**
     * Returns TRUE if such property exists in dataModel,
     * Otherwise - FALSE
     *
     * @param string $property
     *
     * @return bool
     */
    public function isDatumExists(string $property): bool
    {
        return array_key_exists($property, $this->dataModel);
    }

    /**
     * Get dataModel property if exists
     *
     * @param string $property
     *
     * @return mixed|null
     */
    public function getDatum(string $property)
    {
        return $this->isDatumExists($property) ? $this->dataModel[$property] : null;
    }

    /**
     * @return array
     */
    public function getDataModel()
    {
        return $this->dataModel;
    }

    /**
     * For consumables or non-consumables, the value is always false.
     *
     * For subscriptions, the options are as follows:
     * - true:  A subscription is in active state and will be automatically renewed
     *          on the next renewal date.
     * - false: A user has canceled the subscription. The user can access the subscribed
     *          content before the next renewal date but will be unable to access
     *          the content after the date unless the user enables automatic renewal.
     *          If a grace period is provided, this value remains TRUE for the subscription
     *          as long as it is still in the grace period. The next settlement date
     *          is automatically extended every day until the grace period ends
     *          or the user changes the payment method.
     *
     * @return bool
     */
    public function isAutoRenewing(): bool
    {
        return $this->dataModel['autoRenewing'];
    }

    /**
     * App ID
     *
     * @return string
     */
    public function getApplicationId(): string
    {
        return $this->dataModel['applicationId'];
    }

    /**
     * Product type. The options are as follows:
     * 0: consumable
     * 1: non-consumable
     * 2: subscription
     *
     * @return int
     */
    public function getKind(): int
    {
        return $this->dataModel['kind'];
    }
    /**
     * App package name.
     *
     * @return string|null
     */
    public function getPackageName(): ?string
    {
        return $this->dataModel['packageName'] ?? null;
    }

    /**
     * Product ID. Each product must have a unique ID,
     * which is maintained in the PMS or passed when the app initiates a purchase.
     *
     * @return string
     */
    public function getProductId(): string
    {
        return $this->dataModel['productId'];
    }

    /**
     * Order ID, which uniquely identifies a transaction and is generated
     * by the Huawei IAP server during payment.
     *
     * A different order ID is generated for each fee deduction.
     *
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->dataModel['orderId'];
    }

    /**
     * ID of the subscription group to which a subscription belongs.
     *
     * @note Returned only in the subscription scenario.
     * @return string|null
     */
    public function getProductGroup(): ?string
    {
        return $this->dataModel['productGroup'] ?? null;
    }

    /**
     * Timestamp of the purchase time, which is the number of milliseconds
     * from 00:00:00 on January 1, 1970 to the purchase time.
     *
     * If the purchase is not complete, this parameter is left empty.
     *
     * @note Returned only in the subscription scenario.
     * @return int|null
     */
    public function getPurchaseTime(): ?int
    {
        return $this->dataModel['purchaseTime'] ?? null;
    }

    /**
     * Transaction status. The options are as follows:
     * -1: initialized
     *  0: purchased
     *  1: canceled
     *  2: refunded
     *
     * @return int
     */
    public function getPurchaseState(): int
    {
        return $this->dataModel['purchaseState'];
    }

    /**
     * Information stored on the merchant side, which is passed by the app
     * when the payment API is called.
     *
     * @return null|string
     */
    public function getDeveloperPayload(): ?string
    {
        return $this->dataModel['developerPayload'] ?? null;
    }

    /**
     * Challenge defined when an app initiates a consumption request.
     * The challenge uniquely identifies the consumption request
     * and exists only for one-off products.
     *
     * @return string|null
     */
    public function getDeveloperChallenge(): ?string
    {
        return $this->dataModel['developerChallenge'] ?? null;
    }

    /**
     * Purchase token, which uniquely identifies the mapping between a product and a user.
     * It is generated by the Huawei IAP server when the payment is complete.
     *
     * This parameter uniquely identifies the mapping between a product and a user.
     * It does not change when the subscription is renewed.
     *
     * If the value needs to be stored, you are advised to reserve 128 characters.
     *
     * @return string
     */
    public function getPurchaseToken(): string
    {
        return $this->dataModel['purchaseToken'];
    }

    /**
     * Purchase type. The options are as follows:
     * 0: in the sandbox
     * 1: in the promotion period (currently unsupported)
     *
     * This parameter is not returned during formal purchase.
     *
     * @return int
     */
    public function getPurchaseType(): ?int
    {
        return $this->dataModel['purchaseType'] ?? null;
    }

    /**
     * Currency. The value must comply with the ISO 4217 standard.
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->dataModel['currency'] ?? null;
    }

    /**
     * Value after the actual price of a product is multiplied by 100.
     * The actual price is accurate to two decimal places.
     *
     * For example, if the value of this parameter is 501, the actual product price is 5.01.
     *
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->dataModel['price'] ?? null;
    }

    /**
     * Country or region code, which is used to identify a country or region.
     * The value must comply with the ISO 3166 standard.
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->dataModel['country'] ?? null;
    }

    /**
     * Transaction order ID.
     *
     * @return string|null
     */
    public function getPayOrderId(): ?string
    {
        return $this->dataModel['payOrderId'] ?? null;
    }

    /**
     * Payment method. For details about the value, please refer to
     * @see https://developer.huawei.com/consumer/en/doc/HMSCore-References-V5/server-data-model-0000001050986133-V5#EN-US_TOPIC_0000001050986133__section135412662210
     *
     * @return string|null
     */
    public function getPayType(): ?string
    {
        return $this->dataModel['payType'] ?? null;
    }

    /**
     * Confirmation status. The options are as follows:
     * 0: not confirmed
     * 1: confirmed
     *
     * If this parameter is left empty, no confirmation is required.
     *
     * @return int|null
     * @deprecated This parameter is used only for compatibility with earlier versions.
     *             If your app has the latest version integrated, just ignore this parameter.
     *
     */
    public function getConfirmed(): ?int
    {
        return $this->dataModel['confirmed'] ?? null;
    }

    /**
     * Account type. The options are as follows:
     * 0: HUAWEI ID
     * 1: AppTouch user account
     *
     * @return int|null
     */
    public function getAccountFlag(): ?int
    {
        return $this->dataModel['accountFlag'] ?? null;
    }

    /**
     * Purchase quantity.
     *
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->dataModel['quantity'] ?? null;
    }

    /**
     * Product name
     *
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->dataModel['productName'] ?? null;
    }
}
