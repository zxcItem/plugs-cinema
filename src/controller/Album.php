<?php

namespace plugin\cinema\controller;

use plugin\cinema\model\CinemaAlbum;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 视频专辑
 */
class Album extends Controller
{
    /**
     * 视频专辑管理
     * @auth true
     * @menu true
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public  function index()
    {
        CinemaAlbum::mQuery()->layTable(function () {
            $this->title = '视频专辑管理';
        }, static function (QueryHelper $query) {
            $query->like('name')->dateBetween('create_time');
        });
    }

    /**
     * 添加专辑
     * @auth true
     */
    public function add()
    {
        CinemaAlbum::mForm('form');
    }

    /**
     * 编辑专辑
     * @auth true
     */
    public function edit()
    {
        CinemaAlbum::mForm('form');
    }

    /**
     * 修改专辑状态
     * @auth true
     */
    public function state()
    {
        CinemaAlbum::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除专辑
     * @auth true
     */
    public function remove()
    {
        CinemaAlbum::mDelete();
    }
}