<?php

namespace plugin\cinema\controller;

use plugin\cinema\model\CinemaResource;
use plugin\cinema\model\CinemaType;
use plugin\cinema\model\CinemaTypeItem;
use plugin\cinema\service\ResourceService;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\HttpResponseException;
use think\response\Json;

/**
 * 采集数据
 */
class Gather extends Controller
{
    /**
     * 采集数据管理
     * @auth true
     * @menu true
     */
    public  function index()
    {
        $this->title = '资源采集管理';
        $data = ResourceService::getData(input('resource_id'),['pg'=>input('page')]);
        $this->types = ResourceService::typeHandle(input('resource_id'),$data['class']);
        if (input('output')) return json(['code'=>0,'count'=>$data['total'],'data'=>$data['list']]);
        $this->fetch();
    }

    /**
     * 批量采集
     * @auth true
     */
    public function batch()
    {

    }

    /**
     * 绑定采集分类
     * @auth true
     * @return void
     */
    public function bind()
    {
        if ($this->request->isGet()){
            $this->resource_id = $this->request->param('resource_id');
            $this->resource_type_id = $this->request->param('resource_type_id');
            $this->resource_type_name = $this->request->param('resource_type_name');
            $this->types = CinemaType::items(true);
            $this->fetch();
        }else{
            CinemaTypeItem::mk()->save($this->request->post());
            $this->success('绑定成功');
        }
    }

    /**
     * 解绑采集分类
     * @auth true
     * @return void
     */
    public function unbind()
    {
        CinemaTypeItem::mk()->where([
            'resource_id'=>input('resource_id'),
            'resource_type_id'=>input('resource_type_id')
        ])->delete();
        $this->success('解绑成功');
    }
}