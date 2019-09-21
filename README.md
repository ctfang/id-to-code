# id-to-code


自增int类型的id可逆加密算法

例如mysql自增字段,如果直接在接口中暴露,会暴露出您的系统业务量

特别是订单号,用户id等直接显示,会直接透露你公司的真实运营信息

这时候可以使用 `uuid` 或者 `时间+机器id+进程id+进程内自增+随机数` 编码解决

但是这样处理的code实在太长,而且数据库处理速度没有自增id快,这时候可以使用加密算法,把id转成code给前端,前端传参时候再把code转id完美解决

## Install

- composer command

```bash
composer require ctfang/id-to-code
```

## 使用

生成加密模板
```php
<?php

use IdCode\CodeGen;
use IdCode\CodeService;

require_once __DIR__."/../src/CodeService.php";
require_once __DIR__."/../src/CodeGen.php";
// 最短长度
$minLen = 8;
// 纯数字模式
$mode   = CodeService::numMode; // numMode | chrModel

if ( !file_exists('int.config.json') ){
    file_put_contents('int.config.json',(new CodeGen())->genKey($minLen,$mode));
}

```

业务使用

```php
<?php

use IdCode\CodeService;

$code = new CodeService('int.config.json');

$ok = true;
// 加密解密一亿次
for ($int=0;$int<100000000;$int++){
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
```
每一个 `int` 都转化类似 `71869931` 的code

## 原理

 