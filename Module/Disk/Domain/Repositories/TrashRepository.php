<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Repositories;

use Module\Disk\Domain\Model\DiskModel;

class TrashRepository {

    public static function getList() {
        return DiskModel::with('file')
            ->auth()
            ->where('deleted_at', '>', 0)
            ->orderBy('deleted_at', SORT_DESC)
            ->thenBy('id', SORT_ASC)
            ->page();
    }

    public static function reset(array|int $id) {
        $model_list = DiskModel::auth()->whereIn('id', (array)$id)
            ->where('deleted_at', '>', 0)->get();
        foreach ($model_list as $item) {
            $item->resetThis();
        }
    }

    public static function remove(array|int $id) {
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