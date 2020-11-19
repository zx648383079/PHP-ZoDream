<?php
namespace Module\Disk\Domain\Repositories;

use Module\Disk\Domain\Model\ShareModel;

class ShareRepository {

    public static function publicList() {
        return ShareModel::with('user')->where('mode', 'public')->orderBy('created_at desc')->page();
    }

    public static function getList() {
        return ShareModel::with('user')->where('mode', 'public')->orderBy('created_at desc')->page();
    }

    public static function myList() {
        return ShareModel::with('user')->where('user_id', auth()->id())
            ->orderBy('created_at desc')->page();
    }

    public static function remove($id) {
        ShareModel::auth()->whereIn('id', (array)$id)->delete();
    }
}