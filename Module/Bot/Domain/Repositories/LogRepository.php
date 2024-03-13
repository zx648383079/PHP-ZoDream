<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Module\Bot\Domain\Model\MessageHistoryModel;

class LogRepository {

    public static function getList(int $bot_id, bool $mark = false) {
        AccountRepository::isSelf($bot_id);
        return MessageHistoryModel::with('to_user', 'from_user')->where('bot_id', $bot_id)
            ->when($mark !== false, function ($query) use ($mark) {
                $query->where('is_mark', intval($mark));
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function manageList(int $bot_id = 0, bool $mark = false) {
        return MessageHistoryModel::with('to_user', 'from_user')
            ->when($bot_id > 0, function ($query) use ($bot_id) {
                $query->where('bot_id', $bot_id);
            })
            ->when($mark !== false, function ($query) use ($mark) {
                $query->where('is_mark', intval($mark));
            })->orderBy('id', 'desc')
            ->page();
    }
    public static function mark(int $id) {
        $log = MessageHistoryModel::findOrThrow($id, '记录不存在');
        AccountRepository::isSelf($log->bot_id);
        MessageHistoryModel::where('id', $id)->updateBool('mark');
    }

    public static function remove(int $id) {
        $log = MessageHistoryModel::findOrThrow($id, '记录不存在');
        AccountRepository::isSelf($log->bot_id);
        MessageHistoryModel::where('id', $id)->delete();
    }
}