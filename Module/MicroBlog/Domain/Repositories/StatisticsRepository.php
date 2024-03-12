<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Repositories;

use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\MicroBlog\Domain\Model\TopicModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $post_count = MicroBlogModel::query()->count();
        $post_today = $post_count < 1 ? 0 : MicroBlogModel::where('created_at', '>=', $todayStart)->count();
        $comment_count = MicroRepository::comment()->query()->count();
        $comment_today = $comment_count < 1 ? 0 : MicroRepository::comment()->query()
            ->where('created_at', '>=', $todayStart)->count();
        $topic_count = TopicModel::query()->count();
        $topic_today = $topic_count < 1 ? 0 : TopicModel::where('created_at', '>=', $todayStart)->count();
        return compact('post_count', 'post_today',
            'comment_count', 'comment_today',
            'topic_count', 'topic_today');
    }

    public static function userCount(int $user): array {
        return [
            [
                'name' => '微博文数量',
                'count' => MicroBlogModel::where('user_id', $user)->count(),
                'unit' => '篇',
            ],
            [
                'name' => '微博文评论',
                'count' => MicroRepository::comment()->query()->where('user_id', $user)->count(),
                'unit' => '条',
            ],
        ];
    }
}