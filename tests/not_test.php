<?php

use IdCode\CodeConfig;
use IdCode\CodeGen;
use IdCode\CodeService;

require_once __DIR__."/../src/CodeService.php";
require_once __DIR__."/../src/CodeGen.php";
require_once __DIR__."/../src/CodeConfig.php";
require_once __DIR__."/../src/Mode/BaseMode.php";
require_once __DIR__."/../src/Mode/NotMode.php";

if ( !file_exists('not.config.json') ){
    file_put_contents('not.config.json',(new CodeGen())->genKey(8,CodeConfig::notMode));
}

$code = new CodeService('not.config.json');

for ($int=0;$int<1000;$int++){
    $base = $int;
    echo $base." = ";
    $str = $code->toString($base);

    echo $str." = ";
    echo $code->toInt($str)."\n";
}
