<?php

namespace plugin\cinema\controller;

use plugin\cinema\model\CinemaVideoPlay;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 视频剧集
 */
class Play extends Controller
{
    /**
     * 视频剧集管理
     * @auth true
     * @menu true
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public  function index()
    {
        $this->line_id = trim($this->get['line_id'] ?? 0);
        CinemaVideoPlay::mQuery()->layTable(function () {
            $this->title = '视频剧集管理';
            $this->lines = array_unique(CinemaVideoPlay::mk()->where('video_id',input('video_id'))->column('line_name','line_id'));
        }, static function (QueryHelper $query) {
            $query->with(['video'])->equal('video_id,status,line_id')->like('name')->dateBetween('create_time');
        });
    }

    /**
     * 添加剧集
     * @auth true
     */
    public function add()
    {
        CinemaVideoPlay::mForm('form');
    }

    /**
     * 编辑剧集
     * @auth true
     */
    public function edit()
    {
        CinemaVideoPlay::mForm('form');
    }

    /**
     * 修改剧集状态
     * @auth true
     */
    public function state()
    {
        CinemaVideoPlay::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除剧集
     * @auth true
     */
    public function remove()
    {
        CinemaVideoPlay::mDelete();
    }
}