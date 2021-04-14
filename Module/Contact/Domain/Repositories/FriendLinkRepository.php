<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Weights\FriendLink;

class FriendLinkRepository {
    public static function getList(string $keywords = '') {
        return FriendLinkModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'email', 'url', 'brief']);
        })
            ->orderBy([
                'status' => 'asc',
                'id' => 'desc'
            ])->page();
    }

    public static function get(int $id) {
        return FriendLinkModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = FriendLinkModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function toggle(int $id) {
        $model = static::get($id);
        $model->status = $model->status > 0 ? 0  : 1;
        $model->save();
        cache()->delete(FriendLink::KEY);
        return $model;
    }

    public static function remove(int $id) {
        FriendLinkModel::where('id', $id)->delete();
        cache()->delete(FriendLink::KEY);
    }
}