<?php

include "bootstrap.php";

$Server = new \Crudy\Server\Server('\Continuous\MicroServiceDemo\Auth\Api\Pub');
$Server
    ->cors(new \Crudy\Server\Cors\CorsVo('Access-Control-Allow-Credentials', 'true'))
    ->cors(new \Crudy\Server\Cors\CorsVo('Access-Control-Expose-Headers', 'set'))
;

$Server->resolve();