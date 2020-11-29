<?php

namespace Huawei\IAP\Tests;

use Huawei\IAP\AuthorizationBuilder;
use PHPUnit\Framework\TestCase;

class AuthorizationBuilderTest extends TestCase
{
    public function testAuthorizationBuilderHeader(): void
    {
        $builder = new AuthorizationBuilder();
        $at = 'testAccessToken';
        $expected = sprintf('Basic %s', base64_encode(sprintf('APPAT:%s', $at)));

        $this->assertSame($expected, $builder->buildAuthorizationHeader($at));
    }
}
