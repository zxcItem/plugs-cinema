<?php

namespace plugin\cinema\model;

use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 视频题材模型
 */
class CinemaTheme extends Abs
{

    public static function getIds(int $type_id,string $string)
    {
        if ($string){
            $string = explode(',', $string);
            foreach($string as &$value) {
                $names = self::mk()->column('type_id,id','name');
                if (!empty($names[$value]) && $names[$value]['id']){
                    $id[] = $names[$value]['id'];
                }else{
                    $id[] = self::mk()->insertGetId(['type_id' => $type_id, 'name' => $value]);
                }
            }
            return implode(',',$id);
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

    /**
     * 多选数据
     * @param $string
     * @return array|CinemaTheme[]|Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function xSelect($string)
    {
        $list = self::mk()->field('id as value,name')->select();
        $arr = explode(",", $string);
        foreach($list as &$item) $item['selected'] = in_array($item['value'], $arr);
        return $list;
    }
}