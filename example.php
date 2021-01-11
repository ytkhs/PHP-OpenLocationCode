<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/OpenLocationCode.php';


$olc = new OpenLocationCode();
// $code = $olc->encode(47.0000625,8.0000625);
// dump($code);

$code_area = $olc->decode("8FVC2222+22GCCCCC");
dump($code_area);
# => lat_lo: 47.000062496 long_lo: 8.0000625 lat_hi: 47.000062504 long_hi: 8.000062530517578 code_len: 16

# => "8FVC2222+22"
# Encodes any latitude and longitude into a Plus+Codes with preferred length
$code = $olc->encode(47.0000625,8.0000625, 16);
#dump($code);
# => "8FVC2222+22GCCCCC"

#dump($olc->isShortCode("8FVC2222+22GCCCCC"));