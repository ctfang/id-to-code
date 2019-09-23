<?php declare(strict_types=1);


namespace IdCode;

use IdCode\Mode\CharMode;
use IdCode\Mode\NotMode;
use IdCode\Mode\NumMode;

/**
 * Class CodeService
 * @package IdCode
 */
class CodeService
{
    protected $handel;

    /**
     * CodeService constructor.
     * @param  string|null  $strOrFile  文件路径或者字符串
     */
    public function __construct(string $strOrFile = null)
    {
        if (!$strOrFile) {
            $arr = json_decode(file_get_contents(__DIR__.'/str.config.json'), true);
        } elseif (file_exists($strOrFile)) {
            $strOrFile = file_get_contents($strOrFile);
            $arr = json_decode($strOrFile, true);
        } else {
            $arr = json_decode($strOrFile, true);
        }

        switch ($arr['mode']) {
            case CodeConfig::chrModel:
                $this->handel = new CharMode($arr);
                break;
            case CodeConfig::numMode:
                $this->handel = new NumMode($arr);
                break;
            case CodeConfig::notMode:
                $this->handel = new NotMode($arr);
                break;
        }
    }

    /**
     * 字符串转数字
     * @param  string  $str
     * @return int
     */
    public function toInt(string $str): int
    {
        return $this->handel->toInt($str);
    }

    /**
     * 数字转字符串
     * @param  int  $id
     * @return string
     */
    public function toString(int $id): string
    {
        return $this->handel->toString($id);
    }
}