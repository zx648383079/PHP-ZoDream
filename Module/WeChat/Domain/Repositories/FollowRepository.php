<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\UserGroupModel;
use Module\WeChat\Domain\Model\UserModel;

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

    public static function manageList(int $wid = 0, string $keywords = '', int $group = 0, bool $blacklist = false) {
        return UserModel::when($wid > 0, function ($query) use ($wid) {
                $query->where('wid', $wid);
            })
            ->when(!empty($blacklist), function ($query) {
                $query->where('is_black', 1);
            })->when($group > 0, function ($query) use ($group) {
                $query->where('group_id', $group);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['note_name', 'nickname']);
            })->orderBy('status', 'desc')->orderBy('is_black', 'asc')
            ->orderBy('subscribe_at', 'desc')->page();
    }

    public static function add(int $wid, string $openid, array $info = []) {
        $model = UserModel::where('openid', $openid)
            ->where('wid', $wid)->first();
        if (empty($model)) {
            $model = new UserModel([
                'openid' => $openid,
                'wid' => $wid,
            ]);
        }
        if (!empty($info)) {
            $model->set($info);
        } else {
            $model->status = UserModel::STATUS_SUBSCRIBED;
            $model->subscribe_at = time();
        }
        $model->save();
    }

    public static function delete(int $wid, string $openid) {
        UserModel::where('openid', $openid)
            ->where('wid', $wid)->update([
                'status' => UserModel::STATUS_UNSUBSCRIBED,
                'updated_at' => time()
            ]);
    }

    public static function async(int $wid) {
        AccountRepository::isSelf($wid);
        PlatformRepository::entry($wid)
            ->pullUsers(function (array $data) use ($wid) {
                FollowRepository::add($wid, $data['openid'], $data);
            });
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

    public static function groupSave(int $wid, array $data) {
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