<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Domain\Repositories;

use Module\OpenPlatform\Domain\Model\PlatformModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $platform_count = PlatformModel::query()->where('user_id', auth()->id())->count();
        $view_today = 0;
        $view_count = 0;
        return compact('platform_count', 'view_count', 'view_today');
    }
}