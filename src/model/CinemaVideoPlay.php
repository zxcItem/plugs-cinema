<?php

namespace plugin\cinema\model;

use think\model\relation\HasOne;

/**
 * 影视集线路模型
 */
class CinemaVideoPlay extends Abs
{

    /**
     * 获取关联视频的标题
     * @return HasOne
     */
    public function video(): HasOne
    {
        return  $this->hasOne(CinemaVideo::class, 'id', 'video_id')->bind(['video_name'=>'title']);
    }
}