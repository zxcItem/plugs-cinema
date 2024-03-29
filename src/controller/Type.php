<?php

namespace plugin\cinema\controller;

use plugin\cinema\model\CinemaType;
use think\admin\Controller;
use think\admin\extend\DataExtend;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 视频分类
 */
class Type extends Controller
{
    protected $maxLevel = 2;

    /**
     * 视频分类管理
     * @auth true
     * @menu true
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public  function index()
    {
        CinemaType::mQuery()->layTable(function () {
            $this->title = '视频分类管理';
        }, static function (QueryHelper $query) {
            $query->like('name')->dateBetween('create_time');
        });
    }

    /**
     * 列表数据处理
     * @param array $data
     */
    protected function _index_page_filter(array &$data)
    {
        foreach ($data as &$datum) $datum['spc'] = implode(',',DataExtend::getArrSubIds($data,$datum['id']));
        $data = DataExtend::arr2tree($data,'id','pid','children');
        CinemaType::addIsParent($data);
    }


    /**
     * 添加分类
     * @auth true
     */
    public function add()
    {
        CinemaType::mForm('form');
    }

    /**
     * 编辑分类
     * @auth true
     */
    public function edit()
    {
        CinemaType::mForm('form');
    }

    /**
     * 修改分类状态
     * @auth true
     */
    public function state()
    {
        CinemaType::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 表单数据处理
     * @param array $data
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    protected function _form_filter(array &$data)
    {
        if ($this->request->isGet()) {
            $data['pid'] = intval($data['pid'] ?? input('pid', '0'));
            $this->cates = CinemaType::getParentData($this->maxLevel, $data, [
                'id' => '0', 'pid' => '-1', 'name' => '顶部分类',
            ]);
        }
    }

    /**
     * 删除分类
     * @auth true
     */
    public function remove()
    {
        CinemaType::mDelete();
    }

    public function addIsParent(&$categories) {
        foreach ($categories as &$category) {
            // 如果子分类列表不为空，则设置 isParent 为 true，否则为 false
            $category['isParent'] = !empty($category['children']);
            if (empty($category['children'])) $category['children'] = [];
            // 递归调用，处理子分类
            if (!empty($category['children'])) {
                self::addIsParent($category['children']);
            }
        }
    }
}