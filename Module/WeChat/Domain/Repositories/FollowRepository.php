<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\UserGroupModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\User;

class FollowRepository {

    public static function getList(int $wid, string $keywords = '', int $group = 0, bool $blacklist = false) {
        AccountRepository::isSelf($wid);
        return UserModel::where('wid', $wid)
            ->when(!empty($blacklist), function ($query) {
                $query->where('is_black', 1);
            })->when($group > 0, function ($query) use ($group) {
                $query->where('group_id', $group);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['note_name', 'nickname']);
            })->orderBy('status', 'desc')->orderBy('is_black', 'asc')
            ->orderBy('subscribe_at', 'desc')->page();
    }

    public static function async(int $wid) {
        AccountRepository::isSelf($wid);
        $next_openid = null;
        /** @var User $api */
        $api = WeChatModel::findOrThrow($wid, '公众号错误')
            ->sdk(User::class);
        while (true) {
            $openid_list = $api->userList($next_openid);
            if (empty($openid_list['data']['openid'])) {
                break;
            }
            $data = $api->usersInfo($openid_list['data']['openid']);
            foreach ($data['user_info_list'] as $item) {
                WxRepository::saveUser($item, $wid);
            }
            if (empty($openid_list['next_openid'])) {
                break;
            }
            $next_openid = $openid_list['next_openid'];
        }
    }

    public static function searchFans(int $wid, string $keywords = '') {
        AccountRepository::isSelf($wid);
        return UserModel::query()->where('wid', $wid)
            ->where('status', UserModel::STATUS_SUBSCRIBED)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['nickname', 'note_name']);
            })
            ->asArray()
            ->get('id,nickname as name');
    }

    public static function update(int $id, array $data) {
        $model = UserModel::findOrThrow($id, '会员不存在');
        AccountRepository::isSelf($model->wid);
        return ModelHelper::updateField($model, ['is_black', 'note_name', 'remark', 'group_id'], $data);
    }

    public static function search(int $wid, string $keywords = '', int|array $id = 0) {
        AccountRepository::isSelf($wid);
        return SearchModel::searchOption(
            UserModel::query()->where('wid', $wid)->select('id', 'nickname', 'note_name'),
            ['nickname', 'note_name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function groupSearch(int $wid, string $keywords = '', int|array $id = 0) {
        AccountRepository::isSelf($wid);
        return SearchModel::searchOption(
            UserGroupModel::query()->where('wid', $wid),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function groupList(int $wid, string $keywords = '') {
        AccountRepository::isSelf($wid);
        return UserGroupModel::where('wid', $wid)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function groupSave(int $wid, $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['wid']);
        if ($id > 0) {
            $model = UserGroupModel::where('wid', $wid)->where('id', $id)->first();
            if (empty($model)) {
                throw new \Exception('分组不存在');
            }
        } else {
            $model = new UserGroupModel();
            $model->wid = $wid;
        }
        AccountRepository::isSelf($model->wid);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function groupRemove(int $wid, int $id) {
        AccountRepository::isSelf($wid);
        $model = UserGroupModel::where('wid', $wid)->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('分组不存在');
        }
        $model->delete();
    }

}