<?php

namespace Huawei\IAP;

use GuzzleHttp\Client;
use Huawei\IAP\Exception\AuthorizationResponseUnexpectedFormatException;
use Huawei\IAP\Exception\InvalidAuthorizationResponseException;

class AuthorizationBuilder
{
    const AUTHORIZATION_TOKEN_URL = 'https://oauth-login.cloud.huawei.com/oauth2/v2/token';

    /**
     * @return Client
     */
    protected function getClient()
    {
        return new Client();
    }

    /**
     * @param string $accessToken
     *
     * @return string
     */
    public function buildAuthorizationHeader(string $accessToken)
    {
        return sprintf('Basic %s', base64_encode(sprintf('APPAT:%s', $accessToken)));
    }

    /**
     * @param AuthorizationCredentials $credentials
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidAuthorizationResponseException
     * @throws AuthorizationResponseUnexpectedFormatException
     */
    public function getAccessToken(AuthorizationCredentials $credentials): string
    {
        $response = $this->getClient()->request('POST', self::AUTHORIZATION_TOKEN_URL, [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $credentials->getApplicationId(),
                'client_secret' => $credentials->getAppKey(),
            ]
        ]);

        $body = $response->getBody()->getContents();

        $json = \json_decode($body, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidAuthorizationResponseException(
                \sprintf(
                    'Invalid authorization response (%s) from %s',
                    \json_last_error_msg(),
                    self::AUTHORIZATION_TOKEN_URL
                )
            );
        }

        $accessToken = $json['access_token'] ?? null;

        if ($accessToken === null) {
            throw new AuthorizationResponseUnexpectedFormatException(
                \sprintf(
                    'Authorization response unexpected format (%s) from %s',
                    \json_last_error_msg(),
                    self::AUTHORIZATION_TOKEN_URL
                )
            );
        }

        return $accessToken;
    }
}
