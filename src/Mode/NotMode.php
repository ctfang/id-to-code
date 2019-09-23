<?php


namespace IdCode\Mode;

/**
 * 无长度模式
 * @package IdCode\Mode
 */
class NotMode extends BaseMode
{
    /**
     * 字符串转数字
     * @param  string  $str
     * @return int
     */
    public function toInt(string $str): int
    {
        $len = strlen($str);
        $newStr = "";
        $j = $len - 1;
        for ($i = 0; $i < $len; $i++) {
            $newStr .= $this->mapToNum($str{$i}, $j);
            $j--;
        }
        $int = base_convert($newStr,36,10);
        return $int;
    }

    /**
     * 数字转字符串 int => int36 => str
     * @param  int  $id
     * @return string
     */
    public function toString(int $id): string
    {
        $int36 = base_convert($id,10,36);
        $int36 = strtoupper($int36);
        $len = strlen($int36);
        $newStr = "";
        $j = 0;
        for ($i = ($len - 1); $i >= 0; $i--) {
            $newStr = $this->numToMap($int36{$i}, $j).$newStr;
            $j++;
        }

        return $newStr;
    }

    /**
     * 数字转换map映射值
     * @param  int  $num  值
     * @param  int  $salt  位数
     * @return int
     */
    public function numToMap($num, $salt)
    {
        return $this->numTemplate[$salt][$num];
    }

    /**
     * map转换数字映射值
     * @param  int  $num  值
     * @param  int  $salt  位数
     * @return int
     */
    public function mapToNum($num, $salt)
    {
        return $this->numTemplate2[$salt][$num];
    }
}