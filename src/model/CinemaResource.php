<?php

namespace plugin\cinema\model;

/**
 * 资源采集模型
 */
class CinemaResource extends Abs
{

    /**
     * 获取资源采集设置信息
     * @param $key
     * @param string $field
     * @return array
     */
    public static function items($key, string $field = '*'): array
    {
        return self::mk()->column($field,$key);
    }
}