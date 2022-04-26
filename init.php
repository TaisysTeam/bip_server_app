<?php

use Libs\Funcs;

ignore_user_abort(true);
set_time_limit(0);


$funcs = new Funcs();
$redis = new Redis();


$input = file_get_contents('php://input');


$global = (object)[];
$global -> tag = "BIP_SERVER_API";
$global -> ver = "0.3";
$global -> conf = $conf;
$global -> redis = $redis;
$global -> funcs = $funcs;
$global -> map = $Map;
$global -> aes = $AES;
$global -> input = $input;

