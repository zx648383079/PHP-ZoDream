<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;

final class StatisticsRepository {
    public static function subtotal() {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $forum_count = ForumModel::query()->count();
        $thread_count = ThreadModel::query()->count();
        $thread_today = ThreadModel::where('created_at', '>=', $todayStart)->count();
        $post_count = ThreadPostModel::query()->count();
        $post_today = ThreadPostModel::where('created_at', '>=', $todayStart)->count();
        $view_count = ThreadModel::query()->sum('view_count');
        $view_today = ThreadModel::where('created_at', '>=', $todayStart)->sum('view_count');
        return compact('forum_count', 'thread_count', 'thread_today',
            'post_today', 'post_count', 'view_count', 'view_today');
    }

    public static function userCount(int $user): array {
        return [
            [
                'name' => '帖子数量',
                'count' => ThreadModel::where('user_id', $user)->count(),
                'unit' => '篇',
            ],
            [
                'name' => '回帖数量',
                'count' => ThreadPostModel::where('user_id', $user)->count(),
                'unit' => '条',
            ],
        ];
    }
}