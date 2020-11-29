<?php

namespace Huawei\IAP\Exception;

class InvalidValidationResponseException extends HuaweiIAPException
{
    protected $message = 'Response is not a valid json';
}
