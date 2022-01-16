<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\Model\MessageHistoryModel;

class LogRepository {

    public static function getList(int $wid, bool $mark = false) {
        AccountRepository::isSelf($wid);
        return MessageHistoryModel::where('wid', $wid)
            ->when($mark !== false, function ($query) use ($mark) {
                $query->where('mark', intval($mark));
            })
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