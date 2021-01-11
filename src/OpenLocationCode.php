<?php
require_once __DIR__. '/PlusCode.php';
require_once __DIR__. '/CodeArea.php';
require_once __DIR__. '/OpenLocationCodeValidator.php';

class OpenLocationCode
{
    public function isShortCode($code)
    {
        return OpenLocationCodeValidator::isValid($code)
            && strpos($code, PlusCode::SEPARATOR) < PlusCode::SEPARATOR_POSITION
        ;
    }

    public function isFullCode($code)
    {
        return OpenLocationCodeValidator::isValid($code)
            && strpos($code, PlusCode::SEPARATOR) >= PlusCode::SEPARATOR_POSITION
        ;
    }

    public function invalidLength($length)
    {
        return $length < 2 || $length < PlusCode::PAIR_CODE_LENGTH && (($length % 2) == 1);
    }

    public function clipLatitude($latitude)
    {
        return min(90.0, max(-90.0, $latitude));
    }

    public function normalizeLongitude($longitude)
    {
        if ($longitude >= 180) {
            $longitude -= 360;
        }
        if ($longitude < -180) {
            $longitude += 360;
        }

        return $longitude;
    }

    public function precisionByLength($length)
    {
        if ($length < PlusCode::PAIR_CODE_LENGTH) {
            return 20**(floor($length / -2) + 2);
        }

        return (1.0 / ((20**3) * (5**($length - PlusCode::PAIR_CODE_LENGTH))));
    }

    public function encode($latitude, $longitude, $codeLength = PlusCode::PAIR_CODE_LENGTH)
    {
        if ($this->invalidLength($codeLength)) {
            throw new Exception('Invalid Open Location Code(Plus+Codes) length');
        }

        if ($codeLength > PlusCode::MAX_CODE_LENGTH) {
            $codeLength = PlusCode::MAX_CODE_LENGTH;
        }
        $latitude = $this->clipLatitude($latitude);
        $longitude = $this->normalizeLongitude($longitude);
        if ($latitude == 90) {
            $latitude -= $this->precisionByLength($codeLength);
        }

        $code = '';
        $latVal = 90 * PlusCode::PAIR_CODE_PRECISION * PlusCode::LAT_GRID_PRECISION;
        $latVal += $latitude * PlusCode::PAIR_CODE_PRECISION * PlusCode::LAT_GRID_PRECISION;

        $lngVal = 180 * PlusCode::PAIR_CODE_PRECISION * PlusCode::LNG_GRID_PRECISION;
        $lngVal += $longitude * PlusCode::PAIR_CODE_PRECISION * PlusCode::LNG_GRID_PRECISION;

        $latVal = floor($latVal);
        $lngVal = floor($lngVal);

        if ($codeLength > PlusCode::PAIR_CODE_LENGTH) {
            for($i=0; $i<=PlusCode::MAX_CODE_LENGTH - PlusCode::PAIR_CODE_LENGTH - 1; $i++) {
                $index = ($latVal % 5) * 4 + ($lngVal % 4);
                $code = PlusCode::CODE_ALPHABETS[$index] . $code;
                $latVal = $latVal / 5;
                $lngVal = $lngVal / 4;
            }
        } else {
            $latVal = $latVal / PlusCode::LAT_GRID_PRECISION;
            $lngVal = $lngVal / PlusCode::LNG_GRID_PRECISION;
        }

        for($i=0; $i<=PlusCode::PAIR_CODE_LENGTH / 2 -1; $i++) {
            $code = PlusCode::CODE_ALPHABETS[$lngVal % 20] . $code;
            $code = PlusCode::CODE_ALPHABETS[$latVal % 20] . $code;

            $latVal = $latVal / 20;
            $lngVal = $lngVal / 20;

            if ($i == 0) {
                $code = '+' . $code;
            }
        }

        if ($codeLength >= PlusCode::SEPARATOR_POSITION) {
            return substr($code, 0, $codeLength + 1);
        }
        return substr($code, 0, $codeLength) . $str_pad('', PlusCode::SEPARATOR_POSITION - $codeLength) . '+';
    }

    public function decode($code)
    {
        if (!$this->isFullCode($code)) {
            throw new Exception("Open Location Code(Plus+Codes) is not a valid full code: $code");
        }


        $code = str_replace(PlusCode::SEPARATOR, '', $code);
        $code = preg_replace('/' . PlusCode::PADDING . '+/', '', $code);
        $code = strtoupper($code);

        $southLatitude = -90.0;
        $westLongitude = -180.0;

        $latResolution = 400;
        $lngResolution = 400;

        $digit = 0;
        $table = PlusCode::charLookupTable();

        while($digit < min(strlen($code), PlusCode::MAX_CODE_LENGTH)) {
            if ($digit < PlusCode::PAIR_CODE_LENGTH) {
                $latResolution /= 20;
                $lngResolution /= 20;
                $latIndex = ord(substr($code, $digit, 1));
                $southLatitude += $latResolution * $table[$latIndex];

                $lonIndex = ord(substr($code, $digit + 1, 1));
                $westLongitude += $lngResolution * $table[$lonIndex];
                $digit += 2;
            } else {
                $latResolution /= 5;
                $lngResolution /= 4;

                $index = ord(substr($code, $digit, 1));
                $row = $table[$index] / 4;
                $column = $table[$index] % 4;

                $southLatitude += $latResolution * $row;
                $westLongitude += $lngResolution * $column;
                $digit += 1;
            }
        }

        return new CodeArea($southLatitude, $westLongitude, $latResolution, $lngResolution, $digit);
    }
}