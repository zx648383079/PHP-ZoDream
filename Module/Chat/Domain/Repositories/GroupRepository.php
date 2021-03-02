<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;


use Module\Chat\Domain\Model\GroupModel;
use Module\Chat\Domain\Model\GroupUserModel;

class GroupRepository {

    public static function all() {
        $ids = GroupUserModel::where('user_id', auth()->id())->pluck('group_id');
        if (empty($ids)) {
            return [];
        }
        return GroupModel::whereIn('id', $ids)->all();
    }
}