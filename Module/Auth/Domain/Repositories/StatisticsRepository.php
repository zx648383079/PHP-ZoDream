<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;

final class StatisticsRepository {
    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $yesterdayStart = $todayStart - 86400;
        $user_count = UserModel::query()->count();
        $user_today = $user_count < 1 ? 0 : UserModel::where('created_at', '>=', $todayStart)->count();
        $user_yesterday = $user_count < 1 ? 0 : UserModel::where('created_at', '<', $todayStart)
            ->where('created_at', '>=', $yesterdayStart)->count();
        $money_total = AccountLogModel::query()->sum('money');
        $money_today = AccountLogModel::where('created_at', '>=', $todayStart)->sum('money');
        $money_yesterday = AccountLogModel::where('created_at', '<', $todayStart)
            ->where('created_at', '>=', $yesterdayStart)->sum('money');

        $login_today = LoginLogModel::where('status', 1)->where('created_at', '>=', $todayStart)
            ->groupBy('user_id')
            ->count();
        $login_yesterday = LoginLogModel::where('status', 1)->where('created_at', '<', $todayStart)
            ->where('created_at', '>=', $yesterdayStart)
            ->groupBy('user_id')
            ->count();
        return compact('user_count', 'user_today', 'user_yesterday',
            'money_today', 'money_total', 'money_yesterday', 'login_today', 'login_yesterday');
    }
}