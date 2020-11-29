<?php

namespace Huawei\IAP;

/**
 * Class AuthorizationStorage
 * @package Huawei\IAP
 */
class AuthorizationStorage
{
    protected $data;

    /**
     * @param AuthorizationCredentials $credentials
     *
     * @return string|null
     */
    protected function transformCredentialsToKey(AuthorizationCredentials $credentials): ?string
    {
        return sprintf('%s_%s', $credentials->getApplicationId(), $credentials->getAppKey());
    }

    /**
     * @param AuthorizationCredentials $credentials
     *
     * @return string|null
     */
    public function fetch(AuthorizationCredentials $credentials): ?string
    {
        $key = $this->transformCredentialsToKey($credentials);

        return $this->data[$key] ?? null;
    }

    /**
     * @param AuthorizationCredentials $credentials
     * @param string                   $accessToken
     */
    public function save(AuthorizationCredentials $credentials, string $accessToken): void
    {
        $key = $this->transformCredentialsToKey($credentials);

        $this->data[$key] = $accessToken;
    }
}
