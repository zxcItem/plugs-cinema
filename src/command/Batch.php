<?php

declare (strict_types=1);

namespace plugin\cinema\command;

use plugin\cinema\model\CinemaRegion;
use plugin\cinema\model\CinemaTheme;
use plugin\cinema\model\CinemaTypeItem;
use plugin\cinema\model\CinemaVideo;
use plugin\cinema\model\CinemaVideoLine;
use plugin\cinema\model\CinemaVideoPlay;
use plugin\cinema\service\ResourceService;
use plugin\payment\model\PaymentTransfer;
use think\admin\Command;
use think\admin\Exception;
use think\console\Input;
use think\console\Output;
use think\db\exception\DbException;

/**
 * 根据资源vod_ids采集资源详情
 * @class Batch
 * @package plugin\cinema\command
 */
class Batch extends Command
{
    /**
     * ID采集资源
     * @return void
     */
    protected function configure()
    {
        $this->setName('cinema:batch');
        $this->setDescription('批量资源ID采集数据任务操作');
    }

    /**
     * 执行ID采集资源
     * @param Input $input
     * @param Output $output
     * @throws Exception
     */
    protected function execute(Input $input, Output $output)
    {
        $resource_id = $this->queue->data['resource_id'];
        $map = ['ids'=>$this->queue->data['vod_ids'] ?? '','ac'=>'detail','h'=> $this->queue->data['h'] ?? ''];
        $resource = ResourceService::getData(intval($resource_id),$map);
        $types = CinemaTypeItem::mk()->where('resource_id',$resource_id)->column('type_id,type_pid','resource_type_id');
        [$total, $count, $error] = [$resource['total'], 0, 0];
        foreach ($resource['list'] as &$model) try {
            $this->queue->message($total, ++$count, sprintf('开始采集【 %s 】资源详情', $model['vod_name']));
            if (!$type = $types[$model['type_id']]['type_id']) continue;
            $region_id = CinemaRegion::region($model['vod_area']);
            $theme = CinemaTheme::getIds($type,$model['vod_class']);
            $this->app->db->transaction(function () use ($model,$resource_id,$type,$region_id,$theme) {
                $video_id = ResourceService::getVideoId(intval($resource_id),$model, $type,$region_id,$theme);
                ResourceService::saveVideoPlay($video_id,$model['vod_play_from'],$model['vod_play_url']);
                ResourceService::saveVideoDown($video_id,$model['vod_down_from'],$model['vod_down_url']);
            });
        } catch (\Exception $exception) {
            $error++;
            $this->queue->message($total, $count, sprintf('采集【%s 】资源失败, %s', $model['vod_name'], $exception->getMessage()), 1);
        }
        $this->setQueueSuccess(sprintf('此次共采集 %d 条资源详情, 其中有 %d 条采集失败。', $total, $error));
    }
}