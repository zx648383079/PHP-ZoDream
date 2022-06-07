<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Repositories;

use Module\ResourceStore\Domain\Models\ResourceModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $resource_count = ResourceModel::query()->count();
        $resource_today = $resource_count < 1 ? 0 : ResourceModel::where('created_at', '>=', $todayStart)->count();
        $download_today = 0;
        $download_yesterday = 0;
        $download_count = ResourceModel::query()->sum('download_count');
        $view_today = 0;
        $view_count = ResourceModel::query()->sum('view_count');
        $comment = ResourceRepository::comment();
        $comment_today = $comment->query()->where('created_at', '>=', $todayStart)->count();
        $comment_count = $comment->query()->count();
        return compact('resource_count',
            'resource_today', 'download_today', 'download_count', 'download_yesterday',
            'view_count', 'view_today', 'comment_count', 'comment_today');
    }
}