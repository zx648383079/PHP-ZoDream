<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\CMS\Domain\Model\GroupModel;

class GroupRepository {
    public static function getList(string $keywords = '', int $type = 0) {
        return GroupModel::query()
            ->where('type', $type)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->get();
    }

    public static function get(int $id) {
        return GroupModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = GroupModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        GroupModel::where('id', $id)->delete();
    }
}