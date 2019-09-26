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

$ok = true;
for ($int=60466176;$int<70466176;$int++){
    $base = $int;
    $str = $code->toString($int);
    $newInt = $code->toInt($str);

    if ($base != $newInt){
        $ok = false;
        break;
    }
}
if ($ok){
    var_dump("加密 和 解密正常");
};