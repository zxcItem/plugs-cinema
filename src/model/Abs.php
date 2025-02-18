<?php


declare (strict_types=1);

namespace plugin\cinema\model;

use think\admin\Model;

/**
 * 模型抽象类
 * @class Abs
 * @package plugin\cinema\model
 */
abstract class Abs extends Model
{

    /**
     * 格式化输出时间
     * @param mixed $value
     * @return string
     */
    public function getCreateTimeAttr($value): string
    {
        return format_datetime($value);
    }

    /**
     * 格式化输出时间
     * @param mixed $value
     * @return string
     */
    public function getUpdateTimeAttr($value): string
    {
        return format_datetime($value);
    }

    /**
     * 时间写入格式化
     * @param mixed $value
     * @return string
     */
    public function setCreateTimeAttr($value): string
    {
        return is_string($value) ? str_replace(['年', '月', '日'], ['-', '-', ''], $value) : $value;
    }

    /**
     * 时间写入格式化
     * @param mixed $value
     * @return string
     */
    public function setUpdateTimeAttr($value): string
    {
        return $this->setCreateTimeAttr($value);
    }
}