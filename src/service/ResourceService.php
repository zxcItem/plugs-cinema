<?php

namespace plugin\cinema\service;

use plugin\cinema\model\CinemaResource;
use plugin\cinema\model\CinemaTypeItem;
use think\admin\Service;

/**
 * 资源采集
 */
class ResourceService extends Service
{

    /**
     * 资源采集请求
     */
    public static function getData(int $resource_id, array $data = [])
    {
        $resource = CinemaResource::items('id','type,address');
        if ($resource[$resource_id]){
            if($resource[$resource_id]['type'] == 'json'){
                $result = json_decode(http_get($resource[$resource_id]['address'],$data),true);
            }
        }
        return $result ?? [];
    }

    /**
     * 获取分类关联信息
     * @param int $resource_id
     * @param array $resourceType
     * @return array
     */
    public static function typeHandle(int $resource_id,array $resourceType): array
    {
        $types = CinemaTypeItem::typeList(['resource_id'=>$resource_id]);
        $types = array_column($types,'type_name','resource_type_id');
        foreach($resourceType as &$vo) {
            $vo['resource_type_name'] = $types[$vo['type_id']] ?? '';
            $vo['resource_id']        = $resource_id;
        }
        return $resourceType;
    }
}