<?php

use IdCode\CodeConfig;
use IdCode\CodeGen;
use IdCode\CodeService;

require_once __DIR__."/../src/CodeService.php";
require_once __DIR__."/../src/CodeGen.php";
require_once __DIR__."/../src/CodeConfig.php";
require_once __DIR__."/../src/Mode/BaseMode.php";
require_once __DIR__."/../src/Mode/CharMode.php";

if ( !file_exists('str.config.json') ){
    file_put_contents('str.config.json',(new CodeGen())->genKey(6,CodeConfig::chrModel));
}

$code = new CodeService('str.config.json');

$ok = true;
for ($int=0;$int<1000;$int++){
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