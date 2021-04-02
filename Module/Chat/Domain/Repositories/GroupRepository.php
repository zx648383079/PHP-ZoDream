<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\ChatHistoryModel;
use Module\Chat\Domain\Model\GroupModel;
use Module\Chat\Domain\Model\GroupUserModel;
use Module\Chat\Domain\Model\MessageModel;

class GroupRepository {

    public static function all() {
        $ids = GroupUserModel::where('user_id', auth()->id())->pluck('group_id');
        if (empty($ids)) {
            return [];
        }
        return GroupModel::whereIn('id', $ids)->all();
    }

    public static function detail(int $id) {
        if (!self::canable($id)) {
            throw new \Exception('无权限查看');
        }
        $model = GroupModel::findOrThrow($id, '群不存在');
        $model->users = static::users($id);
        return $model;
    }

    public static function users(int $id, string $keywords = '') {
        return GroupUserModel::with('user')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('group_id', $id)->page();
    }

    public static function search(string $keywords = '') {
        $ids = GroupUserModel::where('user_id', auth()->id())->pluck('group_id');
        return GroupModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->whereNotIn('id', $ids)->page();
    }

    public static function canable(int $id) {
        return GroupUserModel::where('user_id', auth()->id())->where('group_id', $id)
            ->count() > 0;
    }

    public static function manageable(int $id) {
        return GroupModel::where('id', $id)
            ->where('user_id', auth()->id())->count() > 0;
    }

    public static function agree(int $user, int $id) {
        if (!static::manageable($id)) {
            throw new \Exception('无权限处理');
        }
        if (GroupUserModel::where('user_id', $user)->where('group_id', $id)
            ->count() > 0) {
            throw new \Exception('已处理过');
        }
        $userModel = UserSimpleModel::find($user);
        if (empty($userModel)) {
            ApplyModel::where('user_id', $user)
                ->where('item_type', 1)
                ->where('item_id', $id)->delete();
            throw new \Exception('用户不存在');
        }
        GroupUserModel::create([
            'group_id' => $id,
            'user_id' => $userModel->id,
            'name' => $userModel->name,
            'role_id' => 0,
            'status' => 5,
        ]);
        ApplyModel::where('user_id', $user)
            ->where('item_type', 1)
            ->where('status', 0)
            ->where('item_id', $id)->update([
                'status' => 1,
                'updated_at' => time()
            ]);
    }

    public static function apply(int $id, string $remark = '') {
        if (static::canable($id)) {
            throw new \Exception('你已加入该群');
        }
        if (GroupModel::where('id', $id)->count() < 1) {
            throw new \Exception('群不存在');
        }
        ApplyModel::create([
            'item_type' => 1,
            'item_id' => $id,
            'remark' => $remark,
            'user_id' => auth()->id(),
            'status' => 0,
        ]);
    }

    public static function applyLog(int $id) {
        if (!static::manageable($id)) {
            throw new \Exception('无权限处理');
        }
        return ApplyModel::with('user')
            ->where('item_type', 1)
            ->where('item_id', $id)
            ->orderBy('status', 'asc')
            ->orderBy('id', 'desc')->page();
    }

    /**
     * 创建群
     * @param array $data
     */
    public static function create(array $data) {
        $model = new GroupModel($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        GroupUserModel::create([
            'user_id' => $model->user_id,
            'name' => auth()->user()->name,
            'group_id' => $model->id,
            'role_id' => 99,
            'status' => 5,
        ]);
        return $model;
    }

    /**
     * 解散群
     * @param int $id
     */
    public static function disband(int $id) {
        $model = GroupModel::find($id);
        if ($model->user_id !== auth()->id()) {
            throw new \Exception('无权限操作');
        }
        $model->delete();
        GroupUserModel::where('group_id', $model->id)->delete();
        MessageModel::where('group_id', $model->id)->delete();
        ChatHistoryModel::where('item_id', $model->id)
            ->where('item_type', 1)->delete();
    }
}