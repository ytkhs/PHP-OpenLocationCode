<?php

class PlusCode
{
    const CODE_ALPHABETS = '23456789CFGHJMPQRVWX';
    const PADDING = '0';
    const SEPARATOR = '+';
    const SEPARATOR_POSITION = 8;
    const MAX_CODE_LENGTH = 15;
    const PAIR_CODE_LENGTH = 10;
    const PAIR_CODE_PRECISION = 8000;

    const LAT_GRID_PRECISION = 5**(self::MAX_CODE_LENGTH - self::PAIR_CODE_LENGTH);
    const LNG_GRID_PRECISION = 4**(self::MAX_CODE_LENGTH - self::PAIR_CODE_LENGTH);

    public static function charLookupTable()
    {
        $chars = str_split(self::CODE_ALPHABETS . self::PADDING . self::SEPARATOR);
        $table = [];

        foreach($chars as $c) {
            $ord = ord($c);
            $ord_lower = ord(strtolower($c));

            $index = strpos(self::CODE_ALPHABETS, $c);

            if ($index !== false) {
                $table[$ord] = $index;
                $table[$ord_lower] =  $index;
            } else {
                $table[$ord] = -1;
            }
        }

        return $table;
    }
}