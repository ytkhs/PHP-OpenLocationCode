<?php

class CodeArea
{
    private $southLatitude;
    private $westLongitude;
    private $latitudeHeight;
    private $longitudeWidth;
    private $codeLength;

    private $latitude_center;
    private $longitude_center;

    public function __construct($southLatitude, $westLongitude, $latitudeHeight, $longitudeWidth, $codeLength)
    {
        $this->southLatitude  = $southLatitude;
        $this->westLongitude  = $westLongitude;
        $this->latitudeHeight = $latitudeHeight;
        $this->longitudeWidth = $longitudeWidth;
        $this->codeLength     = $codeLength;

        $this->latitude_center  = $southLatitude + $latitudeHeight / 2.0;
        $this->longitude_center = $westLongitude + $longitudeWidth / 2.0;
    }

    public function northLatitude()
    {
        return $this->southLatitude + $this->latitudeHeight;
    }

    public function eastLongitude()
    {
        return $this->westLongitude + $this->longitudeWidth;
    }
}