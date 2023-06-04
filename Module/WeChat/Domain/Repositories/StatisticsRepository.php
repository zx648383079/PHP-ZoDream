<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\Model\MessageHistoryModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;

final class StatisticsRepository {

    public static function subtotal(int $wid = 0): array {
        if ($wid > 0) {
            AccountRepository::isSelf($wid);
            $sourceId = [$wid];
        } else {
            $sourceId = WeChatModel::where('user_id', auth()->id())->pluck('id');
        }
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $account_count = WeChatModel::where('user_id', auth()->id())->count();
        $user_today = UserModel::whereIn('wid', $sourceId)->where('created_at', '>=', $todayStart)
            ->where('status', UserModel::STATUS_SUBSCRIBED)->count();
        $user_count = UserModel::whereIn('wid', $sourceId)
            ->where('status', UserModel::STATUS_SUBSCRIBED)->count();
        $message_today = MessageHistoryModel::whereIn('wid', $sourceId)->where('created_at', '>=', $todayStart)
            ->where('type', MessageHistoryModel::TYPE_REQUEST)
            ->count();
        $message_count = MessageHistoryModel::whereIn('wid', $sourceId)
            ->where('type', MessageHistoryModel::TYPE_REQUEST)
            ->count();
        return compact('account_count', 'user_count', 'user_today', 'message_count', 'message_today');
    }

    public static function manageSubtotal(int $wid = 0): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $account_count = WeChatModel::where('user_id', auth()->id())->count();
        $user_today = UserModel::when($wid > 0, function ($query) use ($wid) {
                $query->where('wid', $wid);
            })->where('created_at', '>=', $todayStart)
            ->where('status', UserModel::STATUS_SUBSCRIBED)->count();
        $user_count = UserModel::when($wid > 0, function ($query) use ($wid) {
                $query->where('wid', $wid);
            })
            ->where('status', UserModel::STATUS_SUBSCRIBED)->count();
        $message_today = MessageHistoryModel::when($wid > 0, function ($query) use ($wid) {
                $query->where('wid', $wid);
            })->where('created_at', '>=', $todayStart)
            ->where('type', MessageHistoryModel::TYPE_REQUEST)
            ->count();
        $message_count = MessageHistoryModel::when($wid > 0, function ($query) use ($wid) {
                $query->where('wid', $wid);
            })
            ->where('type', MessageHistoryModel::TYPE_REQUEST)
            ->count();
        return compact('account_count', 'user_count', 'user_today', 'message_count', 'message_today');
    }
}