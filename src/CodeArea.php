<?php

class CodeArea
{
    private $south_latitude;
    private $west_longitude;
    private $latitude_height;
    private $longitude_width;
    private $code_length;

    private $latitude_center;
    private $longitude_center;

    public function __construct($south_latitude, $west_longitude, $latitude_height, $longitude_width, $code_length)
    {
        $this->south_latitude  = $south_latitude;
        $this->west_longitude  = $west_longitude;
        $this->latitude_height = $latitude_height;
        $this->longitude_width = $longitude_width;
        $this->code_length     = $code_length;

        $this->latitude_center  = south_latitude + latitude_height / 2.0;
        $this->longitude_center = west_longitude + longitude_width / 2.0;
    }

    public function north_latitude()
    {
        return $this->south_latitude + $this->latitude_height;
    }

    public function east_longitude()
    {
        return $this->west_longitude + $this->longitude_width;
    }
}