<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Repositories;

use Module\Disk\Domain\Model\FileModel;
use Module\Disk\Domain\Model\ServerModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $server_count = ServerModel::query()->count();
        $online_count = $server_count > 0 ? ServerModel::query()->where('status', 1)->count() : 0;
        $file_count = FileModel::query()->count();
        $today_count = $file_count > 0 ? FileModel::where('created_at', '>=', $todayStart)->count() : 0;
        return compact('server_count', 'online_count', 'today_count', 'file_count');
    }
}