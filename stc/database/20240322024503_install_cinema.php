<?php

use think\migration\Migrator;
use think\migration\db\Column;

/**
 * 影院系统
 */
class InstallCinema extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->_create_cinema_type();
        $this->_create_cinema_region();
        $this->_create_cinema_theme();
        $this->_create_cinema_album();
        $this->_create_cinema_album_info();
        $this->_create_cinema_video();
        $this->_create_cinema_type_item();
        $this->_create_cinema_video_line();
        $this->_create_cinema_resource();
        $this->_create_cinema_video_play();

    }

    /**
     * 视频分类
     * @class CinemaType
     * @table cinema_type
     * @return void
     */
    private function _create_cinema_type()
    {
        // 当前数据表
        $table = 'cinema_type';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-分类',
        ])
            ->addColumn('pid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '上级分类'])
            ->addColumn('name', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '分类名称'])
            ->addColumn('sort', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '排序权重'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '使用状态'])
            ->addColumn('deleted', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '删除状态'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '更新时间'])
            ->addIndex('pid', ['name' => 'idx_cinema_type_pid'])
            ->addIndex('sort', ['name' => 'idx_cinema_type_sort'])
            ->addIndex('status', ['name' => 'idx_cinema_type_status'])
            ->addIndex('deleted', ['name' => 'idx_cinema_type_deleted'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 视频分类关联
     * @class CinemaTypeItem
     * @table cinema_type_item
     * @return void
     */
    private function _create_cinema_type_item()
    {
        // 当前数据表
        $table = 'cinema_type_item';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-分类关联',
        ])
            ->addColumn('resource_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '资源ID'])
            ->addColumn('resource_type_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '资源分类ID'])
            ->addColumn('type_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '分类ID'])
            ->addColumn('type_pid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '上级分类ID'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '创建时间'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }


    /**
     * 视频地区
     * @class CinemaRegion
     * @table cinema_region
     * @return void
     */
    private function _create_cinema_region()
    {
        // 当前数据表
        $table = 'cinema_region';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-地区',
        ])
            ->addColumn('name', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '地区名称'])
            ->addIndex('name', ['name' => 'idx_cinema_region_name'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 视频题材
     * @class CinemaTheme
     * @table cinema_theme
     * @return void
     */
    private function _create_cinema_theme()
    {
        // 当前数据表
        $table = 'cinema_theme';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-题材',
        ])
            ->addColumn('type_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '分类ID'])
            ->addColumn('name', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '题材名称'])
            ->addIndex('name', ['name' => 'idx_cinema_theme_name'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 视频专辑
     * @class CinemaAlbum
     * @table cinema_album
     * @return void
     */
    private function _create_cinema_album()
    {
        // 当前数据表
        $table = 'cinema_album';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-专辑',
        ])
            ->addColumn('name', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '专辑名称'])
            ->addColumn('cover', 'string', ['limit' => 999, 'default' => '', 'null' => true, 'comment' => '专辑封面'])
            ->addColumn('view', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '浏览量'])
            ->addColumn('recommend', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '是否推荐 0否 1是'])
            ->addColumn('remark', 'string', ['limit' => 999, 'default' => '', 'null' => true, 'comment' => '专辑描述'])
            ->addColumn('sort', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '排序权重'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '使用状态'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '更新时间'])
            ->addIndex('sort', ['name' => 'idx_cinema_album_sort'])
            ->addIndex('status', ['name' => 'idx_cinema_album_status'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 视频关联专辑
     * @class CinemaAlbumInfo
     * @table cinema_album_info
     * @return void
     */
    private function _create_cinema_album_info()
    {
        // 当前数据表
        $table = 'cinema_album_info';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-关联专辑',
        ])
            ->addColumn('album_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '专辑ID'])
            ->addColumn('Cinema_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '视频ID'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '更新时间'])
            ->addIndex('album_id', ['name' => 'idx_cinema_album_info_album_id'])
            ->addIndex('Cinema_id', ['name' => 'idx_cinema_album_info_Cinema_id'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 视频数据
     * @class CinemaVideo
     * @table cinema_video
     * @return void
     */
    private function _create_cinema_video()
    {
        // 当前数据表
        $table = 'cinema_video';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-数据',
        ])
            ->addColumn('resource_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '资源ID'])
            ->addColumn('vod_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '资源采集ID'])
            ->addColumn('title', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '视频标题'])
            ->addColumn('cover', 'string', ['limit' => 999, 'default' => '', 'null' => true, 'comment' => '视频封面'])
            ->addColumn('type_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '分类二级'])
            ->addColumn('type_pid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '分类一级'])
            ->addColumn('theme', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '视频题材'])
            ->addColumn('directors', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '视频导演'])
            ->addColumn('actors', 'string', ['limit' => 999, 'default' => '', 'null' => true, 'comment' => '视频演员'])
            ->addColumn('remark', 'string', ['limit' => 999, 'default' => '', 'null' => true, 'comment' => '视频简介'])
            ->addColumn('letter', 'string', ['limit' => 10, 'default' => '', 'null' => true, 'comment' => '首字母'])
            ->addColumn('douban_id', 'string', ['limit' => 100, 'default' => '', 'null' => true, 'comment' => '豆瓣ID'])
            ->addColumn('douban_score', 'float', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '豆瓣评分'])
            ->addColumn('view', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '浏览量'])
            ->addColumn('note', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '连载状态'])
            ->addColumn('year', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '视频年份'])
            ->addColumn('region_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '地区ID'])
            ->addColumn('total', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '总集数'])
            ->addColumn('recommend', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '是否推荐 0否 1是'])
            ->addColumn('release_time', 'string', ['limit' => 180, 'default' => NULL, 'null' => true, 'comment' => '上映时间'])
            ->addColumn('sort', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '排序权重'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '使用状态'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '更新时间'])
            ->addIndex('sort', ['name' => 'idx_cinema_video_sort'])
            ->addIndex('status', ['name' => 'idx_cinema_video_status'])
            ->addIndex('type_id', ['name' => 'idx_cinema_video_type_id'])
            ->addIndex('type_pid', ['name' => 'idx_cinema_video_type_pid'])
            ->addIndex('region_id', ['name' => 'idx_cinema_video_region_id'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 资源采集
     * @class CinemaResource
     * @table cinema_resource
     * @return void
     */
    private function _create_cinema_resource()
    {
        // 当前数据表
        $table = 'cinema_resource';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-资源采集',
        ])
            ->addColumn('name', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '资源名称'])
            ->addColumn('address', 'string', ['limit' => 999, 'default' => '', 'null' => true, 'comment' => '资源地址'])
            ->addColumn('remark', 'string', ['limit' => 999, 'default' => '', 'null' => true, 'comment' => '资源备注'])
            ->addColumn('type', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '资源类型 1-json,2-xml'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '使用状态'])
            ->addColumn('parse_mod', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '1-播放器模式,2-高级模式,3-json解析模式'])
            ->addColumn('parse_address', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '解析地址，视频播放地址'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '更新时间'])
            ->addIndex('status', ['name' => 'idx_cinema_resource_status'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 播放线路
     * @class CinemaVideoLine
     * @table cinema_video_line
     * @return void
     */
    private function _create_cinema_video_line()
    {
        // 当前数据表
        $table = 'cinema_video_line';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-播放线路',
        ])
            ->addColumn('name', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '名称'])
            ->addColumn('type', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '线路类型：0播放线路1下载线路'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '使用状态'])
            ->addColumn('sort', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '排序权重'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '更新时间'])

            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 影视集线路模型
     * @class CinemaVideoPlay
     * @table cinema_video_play
     * @return void
     */
    private function _create_cinema_video_play()
    {
        // 当前数据表
        $table = 'cinema_video_play';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '视频-集播放线路',
        ])
            ->addColumn('line_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '线路ID'])
            ->addColumn('video_id', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '视频ID'])
            ->addColumn('line_name', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '线路标题'])
            ->addColumn('name', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '名称'])
            ->addColumn('type', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '类型0播放1下载'])
            ->addColumn('url', 'string', ['limit' => 180, 'default' => '', 'null' => true, 'comment' => '播放地址'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '使用状态'])
            ->addColumn('sort', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '排序权重'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => true, 'comment' => '创建时间'])
            ->addIndex('sort', ['name' => 'idx_cinema_video_play_sort'])
            ->addIndex('status', ['name' => 'idx_cinema_video_play_status'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

}
