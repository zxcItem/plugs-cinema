<?php

namespace plugin\cinema\controller;

use plugin\cinema\model\CinemaRegion;
use plugin\cinema\model\CinemaTheme;
use plugin\cinema\model\CinemaType;
use plugin\cinema\model\CinemaVideo;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 视频资源管理
 */
class Video extends Controller
{
    /**
     * 视频资源管理
     * @auth true
     * @menu true
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public  function index()
    {
        CinemaVideo::mQuery()->withoutField('remark,actors')->layTable(function () {
            $this->title = '视频资源管理';
            $this->types = CinemaType::items();
        }, static function (QueryHelper $query) {
            $query->with(['region','type'=>function($type){
                $type->with(['type']);
            }])->like('title')->equal('type_id')->dateBetween('create_time');
        });
    }

    /**
     * 列表数据处理
     * @param array $data
     */
    protected function _index_page_filter(array &$data)
    {
        foreach($data as &$value) $value['theme_name'] = CinemaTheme::themeName($value['theme']);
    }

    /**
     * 添加视频资源
     * @auth true
     */
    public function add()
    {
        CinemaVideo::mForm('form');
    }

    /**
     * 编辑视频资源
     * @auth true
     */
    public function edit()
    {
        CinemaVideo::mForm('form');
    }

    /**
     * 表单数据处理
     * @param array $data
     */
    protected function _form_filter(array &$data)
    {
        if ($this->request->isGet()) {
            $this->cates = CinemaType::items(true);
            $data['cates'] = $data['cates'] ?? [];
            $this->regions = CinemaRegion::item();
            $this->themes = CinemaTheme::xSelect($data['theme']);
        }
    }

    /**
     * 修改视频资源状态
     * @auth true
     */
    public function state()
    {
        CinemaVideo::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除视频资源
     * @auth true
     */
    public function remove()
    {
        CinemaVideo::mDelete();
    }
}