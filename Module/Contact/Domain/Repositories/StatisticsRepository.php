<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;


use Module\Contact\Domain\Model\FeedbackModel;
use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Model\ReportModel;
use Module\Contact\Domain\Model\SubscribeModel;

class StatisticsRepository {

    public static function subtotal() {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $feedback_new = FeedbackModel::where('status', 0)->count();
        $feedback_count = FeedbackModel::query()->count();
        $report_new = ReportModel::where('status', 0)->count();
        $report_count = ReportModel::query()->count();
        $link_today = FriendLinkModel::where('created_at', '>=', $todayStart)->count();
        $link_count = FriendLinkModel::query()->count();
        $subscribe_today = SubscribeModel::where('created_at', '>=', $todayStart)->count();
        $subscribe_count = SubscribeModel::query()->count();
        return compact('feedback_new', 'feedback_count', 'report_count',
            'report_new', 'link_today', 'link_count', 'subscribe_today', 'subscribe_count');
    }
}