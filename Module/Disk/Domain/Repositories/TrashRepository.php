<?php
namespace Module\Disk\Domain\Repositories;

use Module\Disk\Domain\Model\DiskModel;

class TrashRepository {

    public static function getList() {
        return DiskModel::with('file')
            ->auth()
            ->where('deleted_at', '>', 0)
            ->orderBy('deleted_at', 'desc')
            ->orderBy('id', 'asc')
            ->page();
    }

    public static function reset($id) {
        $model_list = DiskModel::auth()->whereIn('id', (array)$id)
            ->where('deleted_at', '>', 0)->get();
        foreach ($model_list as $item) {
            $item->resetThis();
        }
    }

    public static function remove($id) {
        $model_list = DiskModel::auth()->whereIn('id', (array)$id)
            ->where('deleted_at', '>', 0)->get();
        foreach ($model_list as $item) {
            $item->deleteThis();
        }
    }

    public static function clear() {
        DiskModel::auth()
            ->where('deleted_at', '>', 0)
            ->delete();
    }
}