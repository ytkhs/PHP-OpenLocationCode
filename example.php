<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/OpenLocationCode.php';


$olc = new OpenLocationCode();
$code = $olc->encode(47.0000625,8.0000625);
dump($code);

$code_area = $olc->decode($code);
dump($code_area);