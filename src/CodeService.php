<?php


namespace IdCode;

/**
 * Class CodeService
 * @package IdCode
 */
class CodeService
{
    private $minLen = 8;
    /** @var int 长度使用数字控制 */
    public const numMode = 2;
    /** @var int 长度模式是字母 */
    public const chrModel = 1;
    protected $mode = 1;
    protected $modeLen = 2;
    protected $lenTemplate = [];
    protected $lenTemplate2 = [];
    protected $numTemplate = [];
    protected $numTemplate2 = [];

    /**
     * CodeService constructor.
     * @param  string|null  $strOrFile 文件路径或者字符串
     */
    public function __construct(string $strOrFile = null)
    {
        if (!$strOrFile) {
            $arr = json_decode(__DIR__.'/str.config.json', true);
        } elseif (file_exists($strOrFile)) {
            $strOrFile = file_get_contents($strOrFile);
            $arr = json_decode($strOrFile, true);
        } else {
            $arr = json_decode($strOrFile, true);
        }

        $this->lenTemplate = $arr['len'];
        $this->numTemplate = $arr['num'];
        $this->mode = $arr['mode'];
        $this->modeLen = $arr['modeLen'] ?? 2;
        $this->minLen = $arr['minLen'] ?? 6;

        foreach ($this->lenTemplate as $salt => $arr) {
            $this->lenTemplate2[$salt] = array_flip($arr);
        }
        foreach ($this->numTemplate as $salt => $arr) {
            $this->numTemplate2[$salt] = array_flip($arr);
        }
    }

    /**
     * 字符串转数字
     * @param  string  $str
     * @return int
     */
    public function toInt(string $str): int
    {
        $end = substr($str, -1);

        if ($this->mode == self::chrModel) {
            $len = $this->strToLen($this->mapToNum($end, 0), $str{0});
        } else {
            $len = $this->strToLen($this->mapToNum($end, 0), substr($str,0,2));
        }

        $strNum = substr($str, -$len);

        $newStr = "";
        $j = $len - 1;
        for ($i = 0; $i < $len; $i++) {
            $newStr .= $this->mapToNum($strNum{$i}, $j);
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
        $end = $str{$len - 1};

        if ($this->mode == self::chrModel) {
            $lenStr = $this->lenToStr($len, $end);
            if (($this->modeLen - 1) > 0) {
                for ($i = 1; $i <= $this->modeLen - 1; $i++) {
                    $lenStr .= $this->lenToStr($i, $end);
                }
            }
        } else {
            $lenStr = $this->lenToStr($len, $end);
        }
        $secStr = "";
        if (($this->minLen - $this->modeLen) > $len) {
            for ($i = $len; $i < $this->minLen - $this->modeLen; $i++) {
                $secStr .= $this->numToMap($end, $i);
            }
        }

        $newStr = "";
        $j = 0;
        for ($i = ($len - 1); $i >= 0; $i--) {
            $newStr = $this->numToMap($str{$i}, $j).$newStr;
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
        return $this->lenTemplate[$salt][$len];
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