# id-to-code


自增int类型的id可逆加密算法

例如mysql自增字段,如果直接在接口中暴露,会暴露出您的系统业务量,哪怕设置id从100000开始，也可以在下个月再看接口就直接知道你系统一个月的运营量

特别是订单号,用户id等直接显示,会直接透露你公司的真实运营信息

这时候可以使用 `uuid` 或者 `时间+机器id+进程id+进程内自增+随机数` 编码解决

但是这样处理的code实在太长,而且数据库处理速度没有自增id快,这时候可以使用加密算法,把id转成code给前端,前端传参时候再把code转id完美解决

## 使用场景

用户对外uid, 订单号

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
// 数字长度位+纯数字模式
$mode   = CodeService::numMode;
// 字符长度位+纯数字模式
// $mode   = CodeService::chrModel;
// 无长度位+36进制| 不能设置最短位数,但是能在最短的位数表达更大的数量
// $mode   = CodeService::notMode;

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

 根据加密模板,替换数字数据,因为只是替换动作,不需要哈希计算,所有速度很快
 
 加密模板是反人类习惯生成的映射表,正常人类看数字是 123456789 有序理解,但是经过加密模板后,可能变成 589647123 无规律
 
 普通id因为是自增的，很容易看出来，但是经过加密编码，1转2，2转5无规律转换，每个位数独立一个加密模板，转换时加个设定位数盐，就是每次都不一样,如果id数由 997 到 998 中间只有一个数字变更,但是在加密算法中 因为盐的不一样,会把前面 99 两个根本没有变动的数字也做了不同模板的替换,也可以得到全部位数都变动的数值