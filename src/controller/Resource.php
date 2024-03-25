<?php

namespace plugin\cinema\controller;

use plugin\cinema\model\CinemaResource;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 资源采集
 */
class Resource extends Controller
{
    /**
     * 资源采集管理
     * @auth true
     * @menu true
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public  function index()
    {
        CinemaResource::mQuery()->layTable(function () {
            $this->title = '资源采集管理';
        }, static function (QueryHelper $query) {
            $query->like('name')->dateBetween('create_time');
        });
    }

    /**
     * 添加资源采集
     * @auth true
     */
    public function add()
    {
        CinemaResource::mForm('form');
    }

    /**
     * 编辑资源采集
     * @auth true
     */
    public function edit()
    {
        CinemaResource::mForm('form');
    }

    /**
     * 修改资源采集状态
     * @auth true
     */
    public function state()
    {
        CinemaResource::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除资源采集
     * @auth true
     */
    public function remove()
    {
        CinemaResource::mDelete();
    }
}