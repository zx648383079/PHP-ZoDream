<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\Model\MessageHistoryModel;

class LogRepository {

    public static function getList(int $wid, bool $mark = false) {
        AccountRepository::isSelf($wid);
        return MessageHistoryModel::with('to_user', 'from_user')->where('wid', $wid)
            ->when($mark !== false, function ($query) use ($mark) {
                $query->where('is_mark', intval($mark));
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function manageList(int $wid = 0, bool $mark = false) {
        return MessageHistoryModel::with('to_user', 'from_user')
            ->when($wid > 0, function ($query) use ($wid) {
                $query->where('wid', $wid);
            })
            ->when($mark !== false, function ($query) use ($mark) {
                $query->where('is_mark', intval($mark));
            })->orderBy('id', 'desc')
            ->page();
    }
    public static function mark(int $id) {
        $log = MessageHistoryModel::findOrThrow($id, '记录不存在');
        AccountRepository::isSelf($log->wid);
        MessageHistoryModel::where('id', $id)->updateBool('mark');
    }

    public static function remove(int $id) {
        $log = MessageHistoryModel::findOrThrow($id, '记录不存在');
        AccountRepository::isSelf($log->wid);
        MessageHistoryModel::where('id', $id)->delete();
    }
}