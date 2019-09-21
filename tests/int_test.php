<?php

use IdCode\CodeGen;
use IdCode\CodeService;

require_once __DIR__."/../src/CodeService.php";
require_once __DIR__."/../src/CodeGen.php";

if ( !file_exists('int.config.json') ){
    file_put_contents('int.config.json',(new CodeGen())->genKey(8,CodeService::numMode));
}

$code = new CodeService('int.config.json');

$ok = true;
for ($int=0;$int<100;$int++){
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

var_dump($code->toString(1));

// 重复检查
//$arr = [];
//for ($i=0;$i<100000000;$i++){
//    $str = $code->toString($i);
//    if (isset($arr[$str])){
//        die("错误,有重复值");
//    }
//    $arr[$str] = true;
//}
//
//var_dump("重复检查正常");