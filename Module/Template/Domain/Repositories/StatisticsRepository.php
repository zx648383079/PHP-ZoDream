<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Module\Template\Domain\Entities\SiteEntity;
use Module\Template\Domain\Entities\SitePageEntity;
use Module\Template\Domain\Entities\ThemeComponentEntity;

final class StatisticsRepository {
    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $component_count = ThemeComponentEntity::query()->count();
        $component_today = $component_count < 1 ? 0 : ThemeComponentEntity::where('created_at', '>=', $todayStart)->count();
        $site_count = SiteEntity::query()->count();
        $site_today = $site_count < 1 ? 0 : SiteEntity::where('created_at', '>=', $todayStart)->count();
        $page_count = SitepageEntity::query()->count();
        $page_today = $page_count < 1 ? 0 : SitepageEntity::where('created_at', '>=', $todayStart)->count();
        return compact('component_count', 'component_today',
            'site_count', 'site_today', 'page_count', 'page_today');
    }
}