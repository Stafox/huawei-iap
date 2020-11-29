<?php

namespace Huawei\IAP\Tests;

use Huawei\IAP\AuthorizationCredentials;
use PHPUnit\Framework\TestCase;

class AuthorizationCredentialsTest extends TestCase
{
    public function testAuthorizationCredentials(): void
    {
        $credentials = new AuthorizationCredentials(123456789, 'testappkey');
        $this->assertSame(123456789, $credentials->getApplicationId());
        $this->assertSame('testappkey', $credentials->getAppKey());
    }
}
