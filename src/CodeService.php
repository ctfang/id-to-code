<?php


namespace IdCode;

/**
 * Class CodeService
 * @package IdCode
 */
class CodeService
{
    private $minLen = 8;

    protected $configStr = "{\"len\":[[\"S\",\"M\",\"P\",\"A\",\"D\",\"O\",\"I\",\"Y\",\"X\",\"V\",\"T\",\"J\",\"G\",\"N\",\"C\",\"U\",\"Z\",\"H\",\"F\",\"R\",\"Q\",\"E\",\"B\",\"L\",\"K\",\"W\"],[\"W\",\"K\",\"U\",\"R\",\"G\",\"O\",\"M\",\"E\",\"T\",\"S\",\"P\",\"X\",\"J\",\"Z\",\"L\",\"A\",\"I\",\"F\",\"D\",\"C\",\"H\",\"N\",\"Y\",\"B\",\"Q\",\"V\"],[\"G\",\"E\",\"D\",\"H\",\"X\",\"S\",\"A\",\"I\",\"P\",\"B\",\"R\",\"U\",\"Z\",\"L\",\"M\",\"F\",\"C\",\"V\",\"K\",\"O\",\"Q\",\"Y\",\"W\",\"N\",\"T\",\"J\"],[\"C\",\"L\",\"K\",\"S\",\"R\",\"P\",\"I\",\"Q\",\"M\",\"A\",\"B\",\"E\",\"H\",\"G\",\"X\",\"D\",\"Z\",\"Y\",\"W\",\"F\",\"J\",\"V\",\"U\",\"O\",\"N\",\"T\"],[\"C\",\"E\",\"H\",\"O\",\"F\",\"S\",\"T\",\"X\",\"A\",\"I\",\"N\",\"Y\",\"K\",\"Z\",\"V\",\"W\",\"Q\",\"M\",\"J\",\"B\",\"P\",\"G\",\"R\",\"U\",\"L\",\"D\"],[\"B\",\"S\",\"H\",\"I\",\"P\",\"W\",\"C\",\"O\",\"Y\",\"T\",\"F\",\"K\",\"N\",\"A\",\"Z\",\"Q\",\"L\",\"G\",\"M\",\"E\",\"U\",\"X\",\"R\",\"J\",\"D\",\"V\"],[\"A\",\"H\",\"P\",\"F\",\"B\",\"J\",\"E\",\"Z\",\"C\",\"R\",\"D\",\"N\",\"I\",\"S\",\"X\",\"G\",\"Q\",\"L\",\"W\",\"V\",\"U\",\"T\",\"O\",\"M\",\"Y\",\"K\"],[\"H\",\"R\",\"Q\",\"P\",\"B\",\"G\",\"E\",\"W\",\"F\",\"Y\",\"N\",\"L\",\"A\",\"K\",\"Z\",\"D\",\"C\",\"J\",\"O\",\"I\",\"S\",\"T\",\"X\",\"V\",\"U\",\"M\"],[\"E\",\"Y\",\"Q\",\"J\",\"S\",\"R\",\"F\",\"T\",\"Z\",\"U\",\"N\",\"K\",\"A\",\"P\",\"W\",\"D\",\"H\",\"G\",\"O\",\"I\",\"X\",\"B\",\"V\",\"M\",\"L\",\"C\"],[\"S\",\"W\",\"R\",\"L\",\"Y\",\"K\",\"H\",\"M\",\"G\",\"O\",\"X\",\"D\",\"B\",\"V\",\"N\",\"C\",\"E\",\"Q\",\"T\",\"U\",\"A\",\"F\",\"Z\",\"P\",\"J\",\"I\"]],\"num\":[[1,3,4,0,7,8,5,6,9,2],[6,3,1,7,0,2,4,9,5,8],[5,7,4,3,9,8,6,2,0,1],[6,0,9,2,7,3,4,1,8,5],[9,7,3,5,1,8,6,2,0,4],[1,2,9,3,5,7,6,0,4,8],[7,8,9,3,0,4,2,1,5,6],[0,9,6,2,7,5,3,8,1,4],[3,1,6,2,9,8,5,0,7,4],[2,7,8,0,5,6,9,3,1,4]],\"mode\":1,\"modeLen\":2,\"minLen\":6}";
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
            $arr = json_decode($this->configStr, true);
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