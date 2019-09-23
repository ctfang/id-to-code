<?php


namespace IdCode\Mode;


use IdCode\CodeConfig;

/**
 * 数字长度模式
 * @package IdCode\Mode
 */
class NumMode extends BaseMode
{
    /**
     * 字符串转数字
     * @param  string  $str
     * @return int
     */
    public function toInt(string $str): int
    {
        $end = (int) substr($str, -1);

        $len = $this->strToLen($this->mapToNum($end, 0), substr($str, 0, 2));

        $strNum = substr($str, -$len);

        $newStr = "";
        $j = $len - 1;
        for ($i = 0; $i < $len; $i++) {
            $newStr .= $this->mapToNum((int) ($strNum{$i}), $j);
            $j--;
        }

        return (int) $newStr;
    }

    /**
     * 数字转字符串
     * @param  int  $id
     * @return string
     */
    public function toString(int $id): string
    {
        $str = (string) $id;
        $len = strlen($str);
        $end = (int) ($str{$len - 1});

        $lenStr = $this->lenToStr($len, $end);

        $secStr = "";
        if (($this->minLen - $this->modeLen) > $len) {
            for ($i = $len; $i < $this->minLen - $this->modeLen; $i++) {
                $secStr .= $this->numToMap($end, $i);
            }
        }

        $newStr = "";
        $j = 0;
        for ($i = ($len - 1); $i >= 0; $i--) {
            $newStr = $this->numToMap((int) ($str{$i}), $j).$newStr;
            $j++;
        }

        return $lenStr.$secStr.$newStr;
    }

    /**
     * 数字转换map映射值
     * @param  int  $num  值
     * @param  int  $salt  位数
     * @return int
     */
    public function numToMap(int $num, int $salt): int
    {
        return $this->numTemplate[$salt][$num];
    }

    /**
     * map转换数字映射值
     * @param  int  $num  值
     * @param  int  $salt  位数
     * @return int
     */
    public function mapToNum(int $num, int $salt): int
    {
        return $this->numTemplate2[$salt][$num];
    }

    /**
     * 获取长度标识字符
     * @param  int  $len  值|长度
     * @param  int  $salt  盐|使用个位值 0-9
     * @return string
     */
    public function lenToStr(int $len, int $salt): string
    {
        return (string) $this->lenTemplate[$salt][$len];
    }

    /**
     * 获取长度标识字符
     * @param  int  $end  个位数,真实的
     * @param  string  $str  长度标志
     * @return int 返回长度
     */
    public function strToLen(int $end, string $str): int
    {
        return $this->lenTemplate2[$end][$str];
    }
}