<?php

namespace plugin\cinema\model;

use think\model\relation\HasOne;

/**
 * 视频分类关联
 */
class CinemaTypeItem extends Abs
{

    /**
     * 关联分类
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(CinemaType::class, 'id', 'type_id')->bind(['type_name'=>'name']);
    }

    public static function typeList($where)
    {
        return self::mk()->with(['type'])->where($where)->select()->toarray();
    }
}