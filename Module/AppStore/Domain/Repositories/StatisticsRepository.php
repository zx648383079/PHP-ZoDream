<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Repositories;


use Module\AppStore\Domain\Models\AppModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $app_count = AppModel::query()->count();
        $app_today = $app_count < 1 ? 0 : AppModel::where('created_at', '>=', $todayStart)->count();
        $download_today = 0;
        $download_yesterday = 0;
        $download_count = AppModel::query()->sum('download_count');
        $view_today = 0;
        $view_count = AppModel::query()->sum('view_count');
        $comment = AppRepository::comment();
        $comment_today = $comment->query()->where('created_at', '>=', $todayStart)->count();
        $comment_count = $comment->query()->count();
        return compact('app_count',
            'app_today', 'download_today', 'download_count', 'download_yesterday',
            'view_count', 'view_today', 'comment_count', 'comment_today');
    }

    public static function userCount(int $user): array {
        return [
            [
                'name' => '应用数量',
                'count' => AppModel::where('user_id', $user)->count(),
                'unit' => '个',
            ],
            [
                'name' => '应用评论',
                'count' => AppRepository::comment()->query()->where('user_id', $user)->count(),
                'unit' => '条'
            ]
        ];
    }
}