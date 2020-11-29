<?php

namespace Huawei\IAP\Tests;

use Huawei\IAP\AuthorizationCredentials;
use Huawei\IAP\AuthorizationStorage;
use PHPUnit\Framework\TestCase;

class AuthorizationStorageTest extends TestCase
{
    public function testAuthorizationCredentials(): void
    {
        $credentials = new AuthorizationCredentials(123456789, 'testappkey');
        $storage = new AuthorizationStorage();
        $storage->save($credentials, 'testAccessToken');
        $this->assertSame('testAccessToken', $storage->fetch($credentials));
    }
}
