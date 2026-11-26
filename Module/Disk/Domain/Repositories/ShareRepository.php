<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Repositories;

use Module\Disk\Domain\Model\ShareModel;

class ShareRepository {

    public static function publicList() {
        return ShareModel::with('user')->where('mode', ShareModel::SHARE_PUBLIC)->orderBy('created_at', SORT_DESC)->page();
    }

    public static function getList() {
        return ShareModel::with('user')->where('mode', ShareModel::SHARE_PUBLIC)->orderBy('created_at', SORT_DESC)->page();
    }

    public static function myList() {
        return ShareModel::with('user')->where('user_id', auth()->id())
            ->orderBy('created_at', SORT_DESC)->page();
    }

    public static function remove(array|int $id) {
        ShareModel::auth()->whereIn('id', (array)$id)->delete();
    }
}