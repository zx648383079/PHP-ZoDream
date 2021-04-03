<?php
namespace Module\Blog\Domain\Repositories;

use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Infrastructure\Support\Html;
use Domain\Repositories\TagRepository as TagBase;

class TagRepository extends TagBase {

    private static $caches = [];

    protected static function query(): SqlBuilder
    {
        return TagModel::query();
    }

    public static function get() {
        return TagModel::query()->get();
    }

    public static function getTags(int $blog_id) {
        if (isset(self::$caches[$blog_id])) {
            return self::$caches[$blog_id];
        }
        $tags = TagRelationshipModel::where('blog_id', $blog_id)->pluck('tag_id');
        if (empty($tags)) {
            return self::$caches[$blog_id] = [];
        }
        $tags = TagModel::query()->whereIn('id', $tags)->asArray()->get('id', 'name');
        return self::$caches[$blog_id] = empty($tags) ? [] : $tags;
    }

    public static function getRelationBlogs(int $blog_id) {
        $tags = self::getTags($blog_id);
        if (empty($tags)) {
            return [];
        }
        $ids = TagRelationshipModel::whereIn('tag_id', array_column($tags, 'id'))
            ->where('blog_id', '<>', $blog_id)->pluck('blog_id');
        if (empty($ids)) {
            return [];
        }
        return BlogRepository::getSimpleList($ids);
    }

    public static function renderTags(int $blog_id, string $content): string {
        $tags = self::getTags($blog_id);
        if (empty($tags)) {
            return $content;
        }
        return self::replaceTags($content, array_column($tags, 'name'));
    }

    private static function replaceTags(string $content, array $tags) {
        if (empty($tags)) {
            return $content;
        }
        $replace = [];
        $i = 0;
        foreach ([
                     '#<code[^\<\>]*>[\s\S]+?</code>#',
                     '#<a[^\<\>]+>[\s\S]+?</a>#',
                     '#<img[^\<\>]+>#'
                 ] as $pattern) {
            $content = preg_replace_callback($pattern, function ($match) use (&$replace, &$i) {
                $tag = '[ZO'.$i ++.'OZ]';
                $replace[$tag] = $match[0];
                return $tag;
            }, $content);
        }
        $content = str_replace($tags, array_map(function ($tag) {
            return Html::a($tag, ['./', 'tag' => $tag]);
        }, $tags), $content);
        return str_replace(array_keys($replace), array_values($replace), $content);
    }

    public static function addTag(int $blog, string|array $tags) {
        static::bindTag(
            TagRelationshipModel::query(),
            $blog,
            'blog_id',
            $tags,
            [
                'blog_count' => 1
            ]
        );
    }

    public static function searchBlogTag(string $keywords): array {
        return TagRepository::searchTag(
            TagRelationshipModel::query(),
            'blog_id',
            $keywords
        );
    }

    protected static function onAfterBind(array $tagId, array $addTag, array $delTag)
    {
        if (!empty($delTag)) {
            TagModel::query()->whereIn('id', $delTag)->updateDecrement('blog_count');
        }
    }

    public static function remove(int $id) {
        TagModel::where('id', $id)->delete();
        TagRelationshipModel::where('tag_id', $id)->delete();
    }
}