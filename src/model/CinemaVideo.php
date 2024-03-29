<?php

namespace plugin\cinema\model;

use think\model\relation\HasOne;

/**
 * 视频数据模型
 */
class CinemaVideo extends Abs
{

    /**
     * 关联视频分类
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(CinemaType::class, 'id', 'type_id');
    }

    /**
     * 关联视频区域
     * @return HasOne
     */
    public function region(): HasOne
    {
        return $this->hasOne(CinemaRegion::class, 'id', 'region_id')->bind(['region_name'=>'name']);
    }
}