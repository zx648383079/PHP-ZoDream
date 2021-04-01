<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;


use Module\Chat\Domain\Model\ApplyModel;

class ApplyRepository {

    public static function groupList(int $id = 0) {
        if (!GroupRepository::manageable($id)) {
            throw new \Exception('无权限处理');
        }
        return ApplyModel::with('user')
            ->where('item_type', 1)
            ->where('item_id', $id)
            ->orderBy('status', 'asc')
            ->orderBy('id', 'desc')->page();
    }

    public static function getList() {
        return ApplyModel::with('user')
            ->where('item_type', 0)
            ->where('item_id', auth()->id())
            ->orderBy('status', 'asc')
            ->orderBy('id', 'desc')->page();
    }

    public static function removeMy() {
        ApplyModel::where('item_type', 0)
            ->where('item_id', auth()->id())->delete();
    }

    public static function removeGroup(int $id) {
        ApplyModel::where('item_type', 1)
            ->where('item_id', $id)->delete();
    }

    public static function agree(int $user) {
        ApplyModel::where('user_id', $user)
            ->where('item_type', 0)
            ->where('status', 0)
            ->where('item_id', auth()->id())->update([
                'status' => 1,
                'updated_at' => time()
            ]);
    }

    public static function agreeGroup(int $user, int $id) {
        ApplyModel::where('user_id', $user)
            ->where('item_type', 1)
            ->where('status', 0)
            ->where('item_id', $id)->update([
                'status' => 1,
                'updated_at' => time()
            ]);
    }

    public static function apply(int $type, int $id, string $remark = '') {
        ApplyModel::create([
            'item_type' => $type,
            'item_id' => $id,
            'remark' => $remark,
            'user_id' => auth()->id(),
            'status' => 0,
        ]);
    }


}