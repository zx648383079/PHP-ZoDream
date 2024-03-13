<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Bot\Domain\Model\UserGroupModel;
use Module\Bot\Domain\Model\UserModel;

class FollowRepository {

    public static function getList(int $bot_id, string $keywords = '', int $group = 0, bool $blacklist = false) {
        AccountRepository::isSelf($bot_id);
        return UserModel::where('bot_id', $bot_id)
            ->when(!empty($blacklist), function ($query) {
                $query->where('is_black', 1);
            })->when($group > 0, function ($query) use ($group) {
                $query->where('group_id', $group);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['note_name', 'nickname']);
            })->orderBy('status', 'desc')->orderBy('is_black', 'asc')
            ->orderBy('subscribe_at', 'desc')->page();
    }

    public static function manageList(int $bot_id = 0, string $keywords = '', int $group = 0, bool $blacklist = false) {
        return UserModel::when($bot_id > 0, function ($query) use ($bot_id) {
                $query->where('bot_id', $bot_id);
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

    public static function add(int $bot_id, string $openid, array $info = []) {
        $model = UserModel::where('openid', $openid)
            ->where('bot_id', $bot_id)->first();
        if (empty($model)) {
            $model = new UserModel([
                'openid' => $openid,
                'bot_id' => $bot_id,
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

    public static function delete(int $bot_id, string $openid) {
        UserModel::where('openid', $openid)
            ->where('bot_id', $bot_id)->update([
                'status' => UserModel::STATUS_UNSUBSCRIBED,
                'updated_at' => time()
            ]);
    }

    public static function async(int $bot_id) {
        AccountRepository::isSelf($bot_id);
        BotRepository::entry($bot_id)
            ->pullUsers(function (array $data) use ($bot_id) {
                FollowRepository::add($bot_id, $data['openid'], $data);
            });
    }

    public static function searchFans(int $bot_id, string $keywords = '') {
        AccountRepository::isSelf($bot_id);
        return UserModel::query()->where('bot_id', $bot_id)
            ->where('status', UserModel::STATUS_SUBSCRIBED)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['nickname', 'note_name']);
            })
            ->asArray()
            ->get('id,nickname as name');
    }

    public static function update(int $id, array $data) {
        $model = UserModel::findOrThrow($id, '会员不存在');
        AccountRepository::isSelf($model->bot_id);
        return ModelHelper::updateField($model, ['is_black', 'note_name', 'remark', 'group_id'], $data);
    }

    public static function search(int $bot_id, string $keywords = '', int|array $id = 0) {
        AccountRepository::isSelf($bot_id);
        return SearchModel::searchOption(
            UserModel::query()->where('bot_id', $bot_id)->select('id', 'nickname', 'note_name'),
            ['nickname', 'note_name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function groupSearch(int $bot_id, string $keywords = '', int|array $id = 0) {
        AccountRepository::isSelf($bot_id);
        return SearchModel::searchOption(
            UserGroupModel::query()->where('bot_id', $bot_id),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function groupList(int $bot_id, string $keywords = '') {
        AccountRepository::isSelf($bot_id);
        return UserGroupModel::where('bot_id', $bot_id)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function groupSave(int $bot_id, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['bot_id']);
        if ($id > 0) {
            $model = UserGroupModel::where('bot_id', $bot_id)->where('id', $id)->first();
            if (empty($model)) {
                throw new \Exception('分组不存在');
            }
        } else {
            $model = new UserGroupModel();
            $model->bot_id = $bot_id;
        }
        AccountRepository::isSelf($model->bot_id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function groupRemove(int $bot_id, int $id) {
        AccountRepository::isSelf($bot_id);
        $model = UserGroupModel::where('bot_id', $bot_id)->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('分组不存在');
        }
        $model->delete();
    }
}