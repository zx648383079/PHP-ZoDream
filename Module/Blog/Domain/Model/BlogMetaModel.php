<?php
namespace Module\Blog\Domain\Model;

use Module\Blog\Domain\Entities\BlogMetaEntity;
use Zodream\Helpers\Json;

/**
 * Class BlogMetaModel
 * @package Module\Blog\Domain\Model
 * @property integer $id
 * @property integer $blog_id
 * @property string $name
 * @property string $content
 */
class BlogMetaModel extends BlogMetaEntity {

    const META_DEFAULT = [
        'is_hide' => 0, // 如果是转载文章是否只显示部分，并链接到原文
        'source_url' => '', // 原文链接
        'source_author' => '', // 原文作者
        'cc_license' => '', // 版权协议
        'weather' => '', // 天气
        'audio_url' => '', // 音频
        'video_url' => '', // 视频
        'comment_status' => 0, // 是否允许评论
    ];

    public static function getMetaWithDefault($id) {
        return static::getMeta($id, self::META_DEFAULT);
    }

    public static function getMeta($id, array $default = []) {
        if ($id < 1) {
            return $default;
        }
        $items = static::query()->where('blog_id', $id)->pluck('content', 'name');
        return array_merge($default, $items);
    }

    public static function saveMeta($blog_id, array $data) {
        if (empty($data)) {
            return;
        }
        $metaKeys = array_keys(self::META_DEFAULT);
        $items = static::getMeta($blog_id);
        $add = [];
        foreach ($data as $name => $content) {
            if (!in_array($name, $metaKeys)) {
                continue;
            }
            if (is_null($content)) {
                $content = '';
            } elseif (is_array($content)) {
                $content = Json::encode($content);
            }
            if (!array_key_exists($name, $items)) {
                if (empty($content)) {
                    continue;
                }
                $add[] = compact('blog_id', 'name', 'content');
                continue;
            }
            if ($content === $items[$name]) {
                continue;
            }
            static::query()->where('blog_id', $blog_id)->where('name', $name)->update([
               'content' => $content
            ]);
        }
        if (empty($add)) {
            return;
        }
        static::query()->insert($add);
    }

    public static function deleteMeta($id) {
        return static::query()->where('blog_id', $id)->delete();
    }
}