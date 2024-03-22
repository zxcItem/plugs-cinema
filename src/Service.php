<?php

declare (strict_types=1);

namespace plugin\cinema;

use think\admin\Plugin;

/**
 * 组件注册服务
 * @class Service
 * @package app\cinema
 */
class Service extends Plugin
{
    /**
     * 定义插件名称
     * @var string
     */
    protected $appName = '影院系统';

    /**
     * 定义安装包名
     * @var string
     */
    protected $package = 'xiaochao/plugs-cinema';


    /**
     * 影院系统菜单配置
     * @return array[]
     */
    public static function menu(): array
    {
        // 设置插件菜单
        $code = app(static::class)->appCode;
        // 设置插件菜单
        return [

        ];
    }
}