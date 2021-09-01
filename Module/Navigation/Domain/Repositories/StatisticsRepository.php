<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories;

use Module\Navigation\Domain\Models\PageModel;
use Module\Navigation\Domain\Models\SiteModel;

final class StatisticsRepository {

    public static function subtotal() {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $site_count = SiteModel::query()->count();
        $site_today = SiteModel::where('created_at', '>=', $todayStart)->count();
        $page_count = PageModel::query()->count();
        $page_today = PageModel::where('created_at', '>=', $todayStart)->count();
        return compact('site_count', 'site_today', 'page_count', 'page_today');
    }
}