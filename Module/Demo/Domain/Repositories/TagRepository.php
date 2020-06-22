<?php
namespace Module\Demo\Domain\Repositories;

use Module\Demo\Domain\Model\TagModel;
use Module\Demo\Domain\Model\TagRelationshipModel;

class TagRepository {

    private static $caches = [];

    public static function get() {
        return TagModel::query()->get();
    }

    public static function getTags($post_id) {
        if (isset(self::$caches[$post_id])) {
            return self::$caches[$post_id];
        }
        $tags = TagRelationshipModel::where('post_id', $post_id)->pluck('tag_id');
        if (empty($tags)) {
            return self::$caches[$post_id] = [];
        }
        $tags = TagModel::query()->whereIn('id', $tags)->asArray()->get('id', 'name');
        return self::$caches[$post_id] = empty($tags) ? [] : $tags;
    }

}