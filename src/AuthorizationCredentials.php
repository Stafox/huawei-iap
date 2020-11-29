<?php

namespace Huawei\IAP;

class AuthorizationCredentials
{
    private $applicationId;
    private $appKey;

    public function __construct(int $applicationId, string $appKey)
    {
        $this->applicationId = $applicationId;
        $this->appKey = $appKey;
    }

    /**
     * @return int
     */
    public function getApplicationId(): int
    {
        return $this->applicationId;
    }

    /**
     * @return string
     */
    public function getAppKey(): string
    {
        return $this->appKey;
    }


}
