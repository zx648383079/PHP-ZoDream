<?php
declare(strict_types=1);
namespace Module\AdSense\Domain\Repositories;

use Module\AdSense\Domain\Entities\AdEntity;
use Module\AdSense\Domain\Entities\AdPositionEntity;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $ad_count = AdEntity::query()->count();
        $ad_today = $ad_count < 1 ? 0 : AdEntity::where('created_at', '>=', $todayStart)->count();
        $display_today = 0;
        $display_count = 0;
        $click_today = 0;
        $click_count = 0;
        $position_count = AdPositionEntity::query()->count();
        return compact('ad_today',
            'ad_count', 'display_count', 'display_today',
            'click_count',
            'click_today', 'position_count');
    }
}