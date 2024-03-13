<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Module\Bot\Domain\Entities\BotEntity;
use Module\Bot\Domain\Model\MessageHistoryModel;
use Module\Bot\Domain\Model\UserModel;
use Module\Bot\Domain\Model\BotModel;

final class StatisticsRepository {

    public static function subtotal(int $bot_id = 0): array {
        if ($bot_id > 0) {
            AccountRepository::isSelf($bot_id);
            $sourceId = [$bot_id];
        } else {
            $sourceId = BotModel::where('user_id', auth()->id())->pluck('id');
        }
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $account_count = BotModel::where('user_id', auth()->id())->count();
        $user_today = UserModel::whereIn('bot_id', $sourceId)->where('created_at', '>=', $todayStart)
            ->where('status', UserModel::STATUS_SUBSCRIBED)->count();
        $user_count = UserModel::whereIn('bot_id', $sourceId)
            ->where('status', UserModel::STATUS_SUBSCRIBED)->count();
        $message_today = MessageHistoryModel::whereIn('bot_id', $sourceId)->where('created_at', '>=', $todayStart)
            ->where('type', MessageHistoryModel::TYPE_REQUEST)
            ->count();
        $message_count = MessageHistoryModel::whereIn('bot_id', $sourceId)
            ->where('type', MessageHistoryModel::TYPE_REQUEST)
            ->count();
        return compact('account_count', 'user_count', 'user_today', 'message_count', 'message_today');
    }

    public static function manageSubtotal(int $bot_id = 0): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $account_count = BotModel::where('user_id', auth()->id())->count();
        $user_today = UserModel::when($bot_id > 0, function ($query) use ($bot_id) {
                $query->where('bot_id', $bot_id);
            })->where('created_at', '>=', $todayStart)
            ->where('status', UserModel::STATUS_SUBSCRIBED)->count();
        $user_count = UserModel::when($bot_id > 0, function ($query) use ($bot_id) {
                $query->where('bot_id', $bot_id);
            })
            ->where('status', UserModel::STATUS_SUBSCRIBED)->count();
        $message_today = MessageHistoryModel::when($bot_id > 0, function ($query) use ($bot_id) {
                $query->where('bot_id', $bot_id);
            })->where('created_at', '>=', $todayStart)
            ->where('type', MessageHistoryModel::TYPE_REQUEST)
            ->count();
        $message_count = MessageHistoryModel::when($bot_id > 0, function ($query) use ($bot_id) {
                $query->where('bot_id', $bot_id);
            })
            ->where('type', MessageHistoryModel::TYPE_REQUEST)
            ->count();
        return compact('account_count', 'user_count', 'user_today', 'message_count', 'message_today');
    }

    public static function userCount(int $user): array {
        return [
            [
                'name' => 'Bot数量',
                'count' => BotEntity::where('user_id', $user)->count(),
                'unit' => '个'
            ]
        ];
    }
}