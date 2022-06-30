<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Module\CMS\Domain\Model\SiteModel;

final class StatisticsRepository {
    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $site_today = SiteModel::where('created_at', '>=', $todayStart)->count();
        $site_count = SiteModel::query()->count();
        $article_count = 0;
        $article_today = 0;
        $form_count = 0;
        $form_today = 0;
        $view_today = 0;
        $view_count = 0;
        $comment_today = 0;
        $comment_count = 0;
        return compact('site_today', 'site_count', 'form_count', 'form_today',
            'article_count', 'article_today',
            'view_today', 'view_count', 'comment_today', 'comment_count');
    }
}