<?php

namespace plugin\cinema\model;

/**
 * 视频题材模型
 */
class CinemaTheme extends Abs
{

    public static function getIds(int $type_id,string $string)
    {
        if ($string){
            $string = explode(',', $string);
            foreach($string as &$value) $data[] = self::mk()->insertGetId(['type_id'=>$type_id,'name'=>$value]);
            return implode(',',$data);
        }
        return '';
    }

    /**
     * 获取题材名称
     * @param $string
     * @return array|string
     */
    public static function themeName($string)
    {

        return $string ? self::mk()->whereIn('id',$string)->column('name') : '';

    }
}