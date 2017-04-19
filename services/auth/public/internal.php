<?php

include "bootstrap.php";

$Server = new \Crudy\Server\Server('\Continuous\MicroServiceDemo\Auth\Api\Internal');
$Server->resolve();