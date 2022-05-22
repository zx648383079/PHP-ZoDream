<?php
declare(strict_types=1);
namespace Module\Video\Domain\Repositories;

use Module\Video\Domain\Models\VideoModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $video_today = VideoModel::where('created_at', '>=', $todayStart)->count();
        $video_count = VideoModel::query()->count();
        $view_today = 0;
        $view_count = 0;
        $comment = VideoRepository::comment();
        $comment_today = $comment->query()->where('created_at', '>=', $todayStart)->count();
        $comment_count = $comment->query()->count();
        return compact('video_today', 'video_count',
            'view_today', 'view_count', 'comment_today', 'comment_count');
    }
}