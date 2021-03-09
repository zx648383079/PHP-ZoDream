<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Repositories;


use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Chat\Domain\Model\FriendClassifyModel;
use Module\Chat\Domain\Model\FriendModel;

class FriendRepository {

    public static function all() {
        $data = FriendClassifyModel::with('users')
            ->where('user_id', auth()->id())->orderBy('id asc')->all();
        if (empty($data)) {
            $data = [FriendClassifyModel::create([
                'name' => '我的好友',
                'user_id' => auth()->id(),
            ])];
        }
        foreach ($data as $key => $item) {
            $data[$key] = $item->toArray();
            $data[$key]['users'] = $item['users'];
            if (!$item->relationLoaded('users')) {
                $data[$key]['users'] = [];
            }
            if ($key < 1) {
                $user = auth()->user();
                array_unshift($data[$key]['users'], new FriendModel([
                    'name' => $user->name,
                    'user' => $user
                ]));
            }
            $data[$key]['count'] = count($data[$key]['users']);
            $data[$key]['online_count'] = 0;
        }
        return $data;
    }

    public static function search(string $keywords = '') {
        $groups = FriendClassifyModel::where('user_id', auth()->id())->pluck('id');
        $exclude = empty($groups) ? [] : FriendModel::whereIn('classify_id', $groups)
            ->pluck('user_id');
        $exclude[] = auth()->id();
        return UserSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->whereNotIn('id', $exclude)->page();
    }

    public static function remove(int $user) {
        FriendModel::where('user_id', $user)->where('belong_id', auth()->id())->delete();
        FriendModel::where('belong_id', $user)->where('user_id', auth()->id())->delete();
    }

    public static function move(int $user, int $group) {
        $groups = FriendClassifyModel::where('user_id', auth()->id())->pluck('id');
        if (!in_array($group, $groups)) {
            throw new \Exception('分组错误');
        }
        $count = FriendModel::whereIn('classify_id', $groups)->where('user_id', $user)->count();
        if ($count < 0) {
            throw new \Exception('好友错误');
        }
        FriendModel::where('user_id', $user)->where('belong_id', auth()->id())->update([
            'classify_id' => $group
        ]);
    }
}