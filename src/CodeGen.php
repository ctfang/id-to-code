<?php


namespace IdCode;

/**
 * 配置生成器
 * @package IdCode
 */
class CodeGen
{
    /** @var string 参与计算的所有字母字符集 */
    protected $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    /**
     * 生成盐和配置
     * @param  int  $minLen  最短长度
     * @param  int  $mode  模式 字母|纯数字
     * @param  int  $modeLen  模式下的长度位, mode =2 有$modeLen位数字标志长度 | mode =1 首字母是长度,剩余补全字母
     * @return string
     */
    public function genKey(int $minLen = 6, $mode = CodeService::chrModel, $modeLen = 2)
    {
        // 长度字母模板
        if( $mode==CodeService::chrModel){
            $config['len'] = $this->getCodeLen();
        }else{
            $config['len'] = $this->getCodeLenInt();
        }

        // 再来16份数字模板
        $config['num'] = $this->getNumMapping();
        $config['mode'] = $mode;
        $config['modeLen'] = $modeLen;
        $config['minLen'] = $minLen;
        return json_encode($config);
    }

    /**
     * 生成随机的数字映射,16份
     * @return array
     */
    private function getNumMapping(): array
    {
        $strLenArr = [];
        for ($j = 0; $j <= 20; $j++) {
            $strArr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
            $temp = [];
            for ($i = 0; $i <= 9; $i++) {
                $key = rand(0, count($strArr) - 1);
                $temp[$i] = $strArr[$key];
                unset($strArr[$key]);
                $strArr = array_values($strArr);
            }
            $strLenArr[$j] = $temp;
        }
        return $strLenArr;
    }

    /**
     * 抽取长度,根据个位数选择长度模板,需要16份模板
     * @param $len
     * @return array
     */
    private function getCodeLen(): array
    {
        $strLenArr = [];
        for ($j = 0; $j <= 20; $j++) {
            $strArr = $this->getStringArr();
            $temp = [];
            for ($i = 0; $i < 26; $i++) {
                $key = rand(0, count($strArr) - 1);
                $temp[$i] = $strArr[$key];
                unset($strArr[$key]);
                $strArr = array_values($strArr);
            }
            $strLenArr[$j] = $temp;
        }
        return $strLenArr;
    }


    /**
     * 抽取长度,根据个位数选择长度模板,需要十份模板
     * @param $len
     * @return array
     */
    private function getCodeLenInt(): array
    {
        $strLenArr = [];
        for ($j = 0; $j <= 20; $j++) {
            $strArr = [];
            while (count($strArr)<26){
                $int = rand(10,99);
                if (!isset($strArr[$int])){
                    $strArr[$int] = $int;
                }
            }
            $strArr = array_values($strArr);
            $temp = [];
            for ($i = 0; $i < 26; $i++) {
                $key = rand(0, count($strArr) - 1);
                $temp[$i] = $strArr[$key];
                unset($strArr[$key]);
                $strArr = array_values($strArr);
            }
            $strLenArr[$j] = $temp;
        }
        return $strLenArr;
    }

    private function getStringArr(): array
    {
        $string = $this->str;
        $arr = [];
        for ($i = 0; $i < strlen($string); $i++) {
            $arr[] = $string{$i};
        }

        return $arr;
    }
}