<?php
require_once __DIR__. '/PlusCode.php';

class OpenLocationCodeValidator
{
    public static function isValid($code)
    {
        return self::isValidLength($code)
            && self::isValidSeparator($code)
            && self::isValidPadding($code)
            && self::isValidCharacter($code)
        ;
    }

    public static function isValidLength($code)
    {
        if (is_null($code)) {
            return false;
        }

        if (strlen($code) < 2 + strlen(PlusCode::SEPARATOR)) {
            return false;
        }

        $parts = explode(PlusCode::SEPARATOR, $code);
        $last = $parts[array_key_last($parts)];

        if (strlen($last) === 1) {
            return false;
        }

        return true;
    }

    public static function isValidSeparator($code)
    {
        if (substr_count($code, PlusCode::SEPARATOR) !== 1) {
            return false;
        }

        $separatorIndex = strpos($code, PlusCode::SEPARATOR);

        if ($separatorIndex > PlusCode::SEPARATOR_POSITION) {
            return false;
        }

        if ($separatorIndex % 2 === 1) {
            return false;
        }

        return true;
    }

    public static function isValidPadding($code)
    {
        if (strpos($code, PlusCode::PADDING) !== false) {

            if (strpos($code, PlusCode::SEPARATOR) < PlusCode::SEPARATOR_POSITION) {
                return false;
            }

            // if PHP8 enabled, use str_starts_with()
            if (substr($code, 0, strlen(PlusCode::PADDING)) === PlusCode::PADDING) {
                return false;
            }

            if (substr($code, -2) === PlusCode::PADDING . PlusCode::SEPARATOR) {
                return false;
            }

            // padding sequences
            preg_match_all('/' . PlusCode::PADDING . '+/', $code , $match);
            $seqs = $match[0];

            if (count($seqs) !== 1 || strlen($seqs[0]) % 2 == 1) {
                return false;
            }

            if (strlen($seqs[0]) > PlusCode::SEPARATOR_POSITION - 2) {
                return false;
            }
        }

        return true;
    }

    public static function isValidCharacter($code)
    {
        $table = PlusCode::charLookupTable();
        foreach(str_split($code) as $c) {
            if(!array_key_exists(ord($c), $table)) {
                return false;
            }
        }
        return true;
    }
}