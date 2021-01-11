<?php
require_once __DIR__. '/PlusCode.php';
require_once __DIR__. '/OpenLocationCodeValidator.php';

class OpenLocationCode
{
    public static function isShortCode($code)
    {
        return OpenLocationValidator::isValid($code)
            && strpos($code, PlusCode::SEPARATOR) < PlusCode::SEPARATOR_POSITION
        ;
    }

    public static function isFullCode($code)
    {
        return OpenLocationValidator::isValid($code)
            && strpos($code, PlusCode::SEPARATOR) >= PlusCode::SEPARATOR_POSITION
        ;
    }

    public function encode()
    {
        return __FUNCTION__;
    }

    public function decode()
    {
        return __FUNCTION__;
    }

    
}