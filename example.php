<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/OpenLocationCode.php';


$olc = new OpenLocationCode();
$code = $olc->encode(47.0000625,8.0000625);
#dump($code);

$code_area = $olc->decode($code);
#dump($code_area);

# => "8FVC2222+22"
# Encodes any latitude and longitude into a Plus+Codes with preferred length
# code = olc.encode(47.0000625,8.0000625, 16)
# => "8FVC2222+22GCCCCC"

dump($olc->isValidCode("8FVC2222+22GCCCCC"));