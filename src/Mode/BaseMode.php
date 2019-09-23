<?php


namespace IdCode\Mode;


abstract class BaseMode
{
    protected $minLen = 8;
    protected $mode = 1;
    protected $modeLen = 2;
    protected $lenTemplate = [];
    protected $lenTemplate2 = [];
    protected $numTemplate = [];
    protected $numTemplate2 = [];

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
    abstract public function toInt(string $str): int;

    /**
     * 数字转字符串
     * @param  int  $id
     * @return string
     */
    abstract public function toString(int $id): string;
}