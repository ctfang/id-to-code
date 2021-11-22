<?php


namespace IdCode\Mode;

/**
 * 无长度模式
 * @package IdCode\Mode
 */
class NotMode extends BaseMode
{
    /**
     * CodeService constructor.
     * @param  array  $arr  文件路径或者字符串
     */
    public function __construct(array $arr)
    {
        $this->lenTemplate = $arr['len'];
        $this->numTemplate = $arr['num'];
        $this->mode = $arr['mode'];
        $this->modeLen = $arr['modeLen'] ?? 2;
        $this->minLen = $arr['minLen'] ?? 6;

        foreach ($this->numTemplate as $len => $arr) {
            foreach ($arr as $end => $arr2) {
                $this->numTemplate2[$len][$end] = array_flip($arr2);
            }
        }
    }

    /**
     * 字符串转数字
     * @param  string  $str
     * @return int
     */
    public function toInt(string $str): int
    {
        $len = strlen($str);
        $end = $str[$len - 1];
        $end = $this->mapToNum($end, 1,0);
        $newStr = "";
        $j = 2;
        // 倒序 654321
        for ($i = ($len - 2); $i >= 0; $i--) {
            $temp = $str[$i];
            $newStr = $this->mapToNum($temp, $j, $end).$newStr;
            $j++;
        }
        $int = base_convert($newStr.$end, 36, 10);
        return $int;
    }

    /**
     * 数字转字符串 int => int36 => str
     * @param  int  $id
     * @return string
     */
    public function toString(int $id): string
    {
        $int36 = base_convert($id, 10, 36);
        $int36 = strtoupper($int36);
        $len = strlen($int36);
        $end = $int36[$len - 1];
        $newStr = $this->numToMap($end, 1, 0);

        $j = 2;
        // 倒序 654321
        for ($i = ($len - 2); $i >= 0; $i--) {
            $temp = $int36[$i];
            $newStr = $this->numToMap($temp, $j, $end).$newStr;
            $j++;
        }

        return $newStr;
    }

    /**
     * 数字转换map映射值
     * @param  int  $num  值
     * @param  int  $len  长度
     * @param  string  $end  尾数
     * @return int
     */
    public function numToMap($num, $len, $end)
    {
        return $this->numTemplate[$len][$end][$num];
    }

    /**
     * map转换数字映射值
     * @param  int  $num  值
     * @param  int  $len  位数
     * @param $end
     * @return int
     */
    public function mapToNum($num, $len,$end)
    {
        return $this->numTemplate2[$len][$end][$num];
    }
}