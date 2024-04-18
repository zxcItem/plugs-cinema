<?php

namespace plugin\cinema\service;

use plugin\cinema\model\CinemaResource;
use plugin\cinema\model\CinemaType;
use plugin\cinema\model\CinemaTypeItem;
use plugin\cinema\model\CinemaVideo;
use plugin\cinema\model\CinemaVideoLine;
use plugin\cinema\model\CinemaVideoPlay;
use think\admin\Service;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 资源采集
 */
class ResourceService extends Service
{

    /**
     * 批量绑定采集分类
     * @param int $resource_id
     * @param array $resourceType
     * @return void
     */
    public static function bindType(int $resource_id,array $resourceType)
    {
        if(CinemaTypeItem::mk()->where('resource_id',$resource_id)->findOrEmpty()){
            $data = array_column($resourceType,'type_id','type_name');
            $types = CinemaType::mk()->column('id','name');
            foreach($data as $key => $value) {
                if (!empty($types[$key])){
                    CinemaTypeItem::mk()->save([
                        'resource_id'=>$resource_id,'resource_type_id'=>$value,'type_id'=>$types[$key]
                    ]);
                }
            }
        }
    }

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

    /**
     * 获取新增数据ID
     * @param int $resource_id
     * @param array $model
     * @param int $type
     * @param int $region_id
     * @param string $theme
     * @return int|string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getVideoId(int $resource_id,array $model,int $type, int $region_id,string $theme)
    {
        $video = CinemaVideo::mk()->where(['type_id'=>$type,'title'=>$model['vod_name']])->find();
        if($video){
            CinemaVideo::mk()->where(['type_id'=>$type,'title'=>$model['vod_name']])
                ->update(['note'=>$model['vod_remarks'],'update_time'=>$model['vod_time']]);
            return $video['id'];
        }
        return CinemaVideo::mk()->insertGetId([
            'resource_id'  => $resource_id,
            'vod_id'       => $model['vod_id'],
            'title'        => $model['vod_name'],
            'cover'        => $model['vod_pic'],
            'type_id'      => $type,
            'directors'    => $model['vod_director'],
            'actors'       => $model['vod_actor'],
            'remark'       => $model['vod_blurb'],
            'letter'       => $model['vod_letter'],
            'douban_id'    => $model['vod_douban_id'],
            'douban_score' => $model['vod_douban_score'],
            'note'         => $model['vod_remarks'],
            'year'         => $model['vod_year'],
            'region_id'    => $region_id,
            'total'        => $model['vod_total'],
            'release_time' => $model['vod_pubdate'],
            'theme'        => $theme,
            'create_time'  => date('Y-m-d H:i:s'),
            'update_time'  => $model['vod_time']
        ]);
    }

    /**
     * 采集播放路线及剧集保存
     * @param int $video_id
     * @param string $vod_play_from
     * @param string $vod_play_url
     * @return void
     */
    public static function saveVideoPlay(int $video_id,string $vod_play_from,string $vod_play_url)
    {
        if($vod_play_from && $vod_play_url){
            $play_from = explode("$$$", $vod_play_from);
            $play_url = explode("$$$", $vod_play_url);
            for ($i = 0; $i < count($play_from); $i++) {
                $result[] = ['name' => $play_from[$i], 'url' => $play_url[$i]];
            }
            foreach($result as &$item){
                $line_id = CinemaVideoLine::mk()->where('name',$item['name'])->value('id');
                if (empty($line_id)) $line_id = CinemaVideoLine::mk()->insertGetId(['name'=>$item['name']]);
                $item['url'] = explode("#", $item['url']);
                foreach($item['url'] as &$vo){
                    if($vo){
                        $video = explode("$", $vo);
                        CinemaVideoPlay::mk()->save([
                            'line_id'  => $line_id,
                            'video_id' => $video_id,
                            'name'     => $video[0],
                            'url'      => $video[1],
                            'line_name'=> $item['name']
                        ]);
                    }
                }
            }
        }
    }

    /**
     * 采集下载路线及剧集保存
     * @param int $video_id
     * @param string $vod_down_from
     * @param string $vod_down_url
     * @return void
     */
    public static function saveVideoDown(int $video_id,string $vod_down_from,string $vod_down_url)
    {
        if($vod_down_from && $vod_down_url){
            $play_from = explode("$$$", $vod_down_from);
            $play_url = explode("$$$", $vod_down_url);
            for ($i = 0; $i < count($play_from); $i++) {
                $result[] = ['name' => $play_from[$i], 'url' => $play_url[$i]];
            }
            foreach($result as &$item){
                $line_id = CinemaVideoLine::mk()->where('name',$item['name'])->value('id');
                if (empty($line_id)) $line_id = CinemaVideoLine::mk()->insertGetId(['name'=>$item['name']]);
                $item['url'] = explode("#", $item['url']);
                foreach($item['url'] as &$vo){
                    if($vo){
                        $video = explode("$", $vo);
                        CinemaVideoPlay::mk()->save([
                            'line_id'  => $line_id,
                            'video_id' => $video_id,
                            'type'     => 1,
                            'name'     => $video[0],
                            'url'      => $video[1],
                            'line_name'=> $item['name']
                        ]);
                    }
                }
            }
        }
    }
}