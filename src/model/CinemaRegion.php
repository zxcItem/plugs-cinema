<?php

namespace plugin\cinema\model;

/**
 * 视频地区
 */
class CinemaRegion extends Abs
{

    /**
     * 获取分类ID
     * @param string $name
     * @return int|mixed|string
     */
    public static function region(string $name)
    {
        if (empty($id = static::mk()->where('name',$name)->value('id'))){
            $id = static::mk()->insertGetId(['name'=>$name]);
        }
        return $id;
    }

    /**
     * 地区列表
     * @return array
     */
    public static function item(): array
    {
        return static::mk()->column('name','id');
    }
}