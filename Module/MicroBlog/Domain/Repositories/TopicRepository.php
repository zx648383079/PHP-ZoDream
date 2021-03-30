<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Repositories\TagRepository as TagBase;
use Module\MicroBlog\Domain\Model\BlogTopicModel;
use Module\MicroBlog\Domain\Model\TopicModel;
use Zodream\Database\Contracts\SqlBuilder;

class TopicRepository extends TagBase {

    protected static function query(): SqlBuilder
    {
        return TopicModel::query();
    }

    public static function newList(string $keywords = '', int $perPage = 20) {
        return static::query()->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, [static::$nameKey], false, '', $keywords);
        })->orderBy('id', 'desc')->page($perPage);
    }

    public static function remove(int $id) {
        TopicModel::where('id', $id)->delete();
        BlogTopicModel::where('topic_id', $id)->delete();
    }

    public static function bind(array $names, int $microId): array {
        static::bindTag(
            BlogTopicModel::query(),
            $microId, 'micro_id',
            $names, [
                'user_id' => auth()->id(),
                'created_at' => time(),
                'updated_at' => time(),
            ],
            'topic_id',
        );
        return TopicModel::whereIn('name', $names)->asArray()->get();
    }
}
