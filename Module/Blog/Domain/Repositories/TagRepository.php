<?php
namespace Module\Blog\Domain\Repositories;

use Domain\Repositories\LocalizeRepository;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Zodream\Database\Contracts\SqlBuilder;
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
        $tags = TagRelationshipModel::where('target_id', $blog_id)->pluck('tag_id');
        if (empty($tags)) {
            return self::$caches[$blog_id] = [];
        }
        $tags = TagModel::query()->whereIn('id', $tags)->asArray()->get('id', 'name');
        return self::$caches[$blog_id] = empty($tags) ? [] : $tags;
    }

    public static function getRelationBlogs(int $blog) {
        $tags = self::getTags($blog);
        if (empty($tags)) {
            return [];
        }
        $ids = TagRelationshipModel::whereIn('tag_id', array_column($tags, 'id'))
            ->where('target_id', '<>', $blog)->pluck('target_id');
        if (empty($ids)) {
            return [];
        }
        return BlogRepository::getSimpleList($ids, language: LocalizeRepository::browserLanguage());
    }


    public static function addTag(int $blog, string|array $tags) {
        static::bindTag(
            TagRelationshipModel::query(),
            $blog,
            'target_id',
            $tags,
            [
                'blog_count' => 1
            ]
        );
    }

    public static function searchBlogTag(string $keywords): array {
        return TagRepository::searchTag(
            TagRelationshipModel::query(),
            'target_id',
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