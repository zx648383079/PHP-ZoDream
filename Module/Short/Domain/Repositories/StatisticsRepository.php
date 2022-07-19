<?php
declare(strict_types=1);
namespace Module\Short\Domain\Repositories;

use Module\Short\Domain\Model\ShortLogModel;
use Module\Short\Domain\Model\ShortUrlModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $video_today = ShortUrlModel::where('created_at', '>=', $todayStart)->count();
        $video_count = ShortUrlModel::query()->count();
        $view_today = ShortLogModel::where('created_at', '>=', $todayStart)->count();
        $view_count = ShortLogModel::query()->count();
        return compact('video_today', 'video_count',
            'view_today', 'view_count');
    }
}