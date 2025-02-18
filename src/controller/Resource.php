<?php

namespace plugin\cinema\controller;

use plugin\cinema\model\CinemaResource;
use plugin\cinema\service\ResourceService;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\HttpResponseException;

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

    /**
     * 检测资源路径
     * @return void
     */
    public function check()
    {
        try {
            $headers['headers'] = ["Content-Type: text/html;charset=utf-8"];
            $data = json_decode(http_get(input('address'),[],$headers),true);
            if ($data['code'] !== 1) $this->error('连接失败！','',2);
            $this->success('连接成功',$data,1);
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error("连接失败：{$exception->getMessage()}",'',2);
        }
    }
}