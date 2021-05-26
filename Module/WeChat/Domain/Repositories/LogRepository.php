<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\Model\MessageHistoryModel;

class LogRepository {

    public static function getList(int $wid, bool $mark = false) {
        return MessageHistoryModel::where('wid', $wid)
            ->when($mark !== false, function ($query) use ($mark) {
                $query->where('mark', intval($mark));
            })
            ->page();
    }

    public static function mark(int $id) {
        MessageHistoryModel::where('id', $id)->updateBool('mark');
    }

    public static function remove(int $id) {
        MessageHistoryModel::where('id', $id)->delete();
    }
}