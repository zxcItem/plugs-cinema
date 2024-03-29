<?php

namespace plugin\cinema\service;

use think\admin\Service;

/**
 * 资源采集
 */
class ResourceService extends Service
{

    public static function checkUrl(string $url)
    {
        $data =json_decode(http_get($url),true);die(dump($data));
        if ($data && $data['code'] == 1){
            return $data;
        }
        return [];
    }
}