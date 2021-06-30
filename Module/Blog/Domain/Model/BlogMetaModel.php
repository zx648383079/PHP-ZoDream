<?php
namespace Module\Blog\Domain\Model;

use Domain\Concerns\TableMeta;
use Module\Blog\Domain\Entities\BlogMetaEntity;

/**
 * Class BlogMetaModel
 * @package Module\Blog\Domain\Model
 * @property integer $id
 * @property integer $blog_id
 * @property string $name
 * @property string $content
 */
class BlogMetaModel extends BlogMetaEntity {

    use TableMeta;

    protected static string $idKey = 'blog_id';
    protected static array $defaultItems = [
        'is_hide' => 0, // 如果是转载文章是否只显示部分，并链接到原文
        'source_url' => '', // 原文链接
        'source_author' => '', // 原文作者
        'cc_license' => '', // 版权协议
        'weather' => '', // 天气
        'audio_url' => '', // 音频
        'video_url' => '', // 视频
        'comment_status' => 0, // 是否允许评论
        'seo_link' => '', // 优雅链接
        'seo_title' => '', // 'seo 优化标题',
        'seo_description' => '', // 'seo 优化描述',
    ];


}