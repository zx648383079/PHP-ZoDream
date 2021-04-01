<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\FriendClassifyModel;
use Module\Chat\Domain\Model\FriendModel;

class FriendRepository {

    public static function getList(string $keywords = '', int $group = -1) {
        return FriendModel::with('user')
            ->where('belong_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when($group >= 0, function ($query) use ($group) {
                $query->where('classify_id', $group);
            })->page();
    }

    public static function all() {
        $groups = FriendClassifyModel::where('user_id', auth()->id())
            ->get();
        $data = [
            1 => [
                'id' => 1,
                'online' => 0,
                'count' => 0,
                'name' => '默认分组',
                'users' => []
            ]
        ];
        foreach ($groups as $item) {
            $data[$item['id']] = [
                'id' => 1,
                'online' => 0,
                'count' => 0,
                'name' => $item['name'],
                'users' => []
            ];
        }
        $data[0] = [
            'id' => 0,
            'online' => 0,
            'count' => 0,
            'name' => '黑名单',
            'users' => []
        ];
        $items = FriendModel::with('user')->where('belong_id', auth()->id())
            ->get();
        foreach ($items as $item) {
            $groupId = isset($data[$item['classify_id']]) ? $item['classify_id'] : 1;
            $data[$groupId]['users'][] = $item;
            $data[$groupId]['count'] ++;
        }
        return array_values($data);
    }

    public static function search(string $keywords = '') {
        $exclude = FriendModel::where('belong_id', auth()->id())
            ->pluck('user_id');
        $exclude[] = auth()->id();
        return UserSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->whereNotIn('id', $exclude)->page();
    }

    /**
     * 关注
     * @param int $user
     * @param int $group
     * @param string $remark
     * @throws \Exception
     */
    public static function follow(int $user, int $group, string $remark = '') {
        $model = FriendModel::where('user_id', $user)->where('belong_id', auth()->id())
            ->first();
        if (!empty($model)) {
            return;
        }
        if (!static::hasClassify($group)) {
            throw new \Exception('选择的分组错误');
        }
        $userModel = UserSimpleModel::where('id', $user)->first();
        if (!$userModel) {
            throw new \Exception('用户不存在');
        }
        $count = FriendModel::where('belong_id', $user)
            ->where('user_id', auth()->id())->count();
        FriendModel::create([
            'name' => $userModel->name,
            'classify_id' => $group,
            'user_id' => $userModel->id,
            'belong_id' => auth()->id(),
            'status' => $group > 0 && $count > 0 ? 1 : 0,
        ]);
        $logCount = ApplyModel::where('item_id', auth()->id())
            ->where('item_type', 0)
            ->where('user_id', $user)->count();
        if ($logCount > 0) {
            ApplyModel::where('item_id', auth()->id())
                ->where('item_type', 0)
                ->where('user_id', $user)->update([
                    'status' => 1,
                    'updated_at' => time(),
                ]);
        }
        if ($count > 0) {
            FriendModel::where('belong_id', $user)
                ->where('user_id', auth()->id())->update([
                   'status' => $group > 0 ? 1 : 0,
                ]);
            return;
        }
        if ($logCount > 0) {
            return;
        }
        ApplyModel::create([
            'item_type' => 0,
            'item_id' => $userModel->id,
            'remark' => $remark,
            'user_id' => auth()->id(),
            'status' => 0,
        ]);
    }

    /**
     * 取消关注
     * @param int $user
     * @throws \Exception
     */
    public static function remove(int $user) {
        FriendModel::where('user_id', $user)->where('belong_id', auth()->id())->delete();
        FriendModel::where('belong_id', $user)
            ->where('user_id', auth()->id())->update([
            'status' => 0,
        ]);
    }

    public static function move(int $user, int $group) {
        if ($group >= 10) {
            $groups = FriendClassifyModel::where('user_id', auth()->id())->pluck('id');
            if (!in_array($group, $groups)) {
                throw new \Exception('分组错误');
            }
        }
        $count = FriendModel::where('belong_id', auth()->id())->where('user_id', $user)->count();
        if ($count < 0) {
            throw new \Exception('好友错误');
        }
        $followed = FriendModel::where('belong_id', $user)
            ->where('user_id', auth()->id())->count();
        FriendModel::where('user_id', $user)->where('belong_id', auth()->id())->update([
            'classify_id' => $group,
            'status' => $followed > 0 && $group > 0 ? 1 : 0
        ]);
        if ($followed > 0) {
            FriendModel::where('belong_id', $user)
                ->where('user_id', auth()->id())->update([
                    'status' => $group < 1 ? 0 : 1,
                ]);
        }
    }

    public static function get(int $user) {
        return FriendModel::where('belong_id', auth()->id())
            ->where('user_id', $user)->first();
    }

    /**
     * 所有分组
     * @return mixed
     * @throws \Exception
     */
    public static function classifyList() {
        $items = FriendClassifyModel::where('user_id', auth()->id())->get();
        $items[] = [
            'id' => 1,
            'name' => '默认分组'
        ];
        $items[] = [
            'id' => 0,
            'name' => '黑名单'
        ];
        return $items;
    }

    public static function classifySave(array $data) {
        $id = isset($data['id']) && $data['id'] > 10 ? $data['id'] : 0;
        unset($data['id']);
        $model = $id > 0 ? FriendClassifyModel::where('user_id', auth()->id())
            ->where('id', $id)->first() : new FriendClassifyModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function classifyRemove(int $id) {
        if ($id < 10) {
            throw new \Exception('系统分组无法删除');
        }
        $model = FriendClassifyModel::where('user_id', auth()->id())
            ->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('无法删除');
        }
        $model->delete();
        FriendModel::where('belong_id', auth()->id())
            ->where('classify_id', $id)
            ->update([
                'classify_id' => 1,
            ]);
    }

    public static function hasClassify(int $id): bool {
        if ($id < 10) {
            return true;
        }
        return FriendClassifyModel::where('user_id', auth()->id())
            ->where('id', $id)->count() > 0;
    }

    /**
     * 我关注的
     * @return int
     * @throws \Exception
     */
    public static function followCount(): int {
        return FriendModel::where('belong_id', auth()->id())
            ->where('classify_id', '>', 0)
            ->count();
    }

    /**
     * 关注我的
     * @return int
     * @throws \Exception
     */
    public static function followedCount(): int {
        return FriendModel::where('user_id', auth()->id())
            ->where('classify_id', '>', 0)
            ->count();
    }

    public static function applyLog() {
        return ApplyModel::with('user')
            ->where('item_type', 0)
            ->where('item_id', auth()->id())
            ->orderBy('status', 'asc')
            ->orderBy('id', 'desc')->page();
    }
}