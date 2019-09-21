<?php

use IdCode\CodeGen;
use IdCode\CodeService;

require_once __DIR__."/../src/CodeService.php";
require_once __DIR__."/../src/CodeGen.php";

if ( !file_exists('str.config.json') ){
    file_put_contents('str.config.json',(new CodeGen())->genKey(6,CodeService::chrModel));
}

$code = new CodeService('str.config.json');

echo $code->toString(12);
echo $code->toString(1);