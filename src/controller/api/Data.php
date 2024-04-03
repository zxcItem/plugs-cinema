<?php

namespace plugin\cinema\controller\api;

use plugin\cinema\model\CinemaRegion;
use plugin\cinema\model\CinemaTheme;
use plugin\cinema\model\CinemaType;
use plugin\cinema\model\CinemaTypeItem;
use plugin\cinema\model\CinemaVideo;
use plugin\cinema\model\CinemaVideoLine;
use plugin\cinema\model\CinemaVideoPlay;
use think\admin\Controller;

class Data extends Controller
{

    public function index()
    {
        $data = json_decode(http_get('https://ikunzyapi.com/api.php/provide/vod/?ac=detail'),true);
        $type = CinemaTypeItem::mk()->column('type_id,type_pid','resource_type_id');
        foreach($data['list'] as &$item){
            $theme = CinemaTheme::getIds($type[$item['type_id']]['type_pid'],$item['vod_class']);
            $video_id = CinemaVideo::mk()->insertGetId([
                'resource_id'  => 1,
                'vod_id'       => $item['vod_id'],
                'title'        => $item['vod_name'],
                'cover'        => $item['vod_pic'],
                'type_id'      => $type[$item['type_id']]['type_id'],
                'directors'    => $item['vod_director'],
                'actors'       => $item['vod_actor'],
                'remark'       => $item['vod_blurb'],
                'letter'       => $item['vod_letter'],
                'douban_id'    => $item['vod_douban_id'],
                'douban_score' => $item['vod_douban_score'],
                'note'         => $item['vod_remarks'],
                'year'         => $item['vod_year'],
                'region_id'    => CinemaRegion::region($item['vod_area']),
                'total'        => $item['vod_total'],
                'release_time' => $item['vod_pubdate'],
                'theme'        => $theme,
            ]);
            $this->vod_play($video_id,$item['vod_play_from'],$item['vod_play_url']);
//            $this->vod_down($video_id,$item['vod_down_from'],$item['vod_down_url']);
        }
        $this->success('获取成功');
    }

    public function vod_play($video_id,$vod_play_from,$vod_play_url)
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
                        CinemaVideoPlay::mk()->insert(['line_id'=>$line_id,'video_id'=>$video_id,'name'=>$video[0],'play_url'=>$video[1]]);
                    }
                }
            }
        }
    }

    public function vod_down($video_id,$vod_down_from,$vod_down_url)
    {
        if ($vod_down_from && $vod_down_url){
            $play_from = explode("$$$", $vod_down_from);
            $play_url = explode("$$$", $vod_down_url);
            for ($i = 0; $i < count($play_from); $i++) {
                $result[] = ['name' => (int)$play_from[$i], 'url' => (int)$play_url[$i]];
            }
            foreach($result as &$item){
                $line_id = CinemaVideoLine::mk()->insertGetId(['video_id'=>$video_id,'name'=>$item['name']]);
                $item['url'] = explode("#", $item['url']);
                foreach($item['url'] as &$vo){
                    if($vo){
                        $video = explode("$", $vo);
                        CinemaVideoPlay::mk()->insert(['line_id'=>$line_id,'video_id'=>$video_id,'name'=>$video[0],'play_url'=>$video[1]]);
                    }
                }
            }
        }
    }

}