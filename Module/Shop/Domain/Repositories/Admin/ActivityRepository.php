<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;

class ActivityRepository {
    public static function getList(int $type, string $keywords = '') {
        $query = ActivityModel::query();
        if (in_array($type, [ActivityModel::TYPE_AUCTION, ActivityModel::TYPE_BARGAIN])) {
            $query->with('goods');
        }
        return $query->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('type', $type)->orderBy('id', 'desc')->page();
    }

    public static function get(int $type, int $id) {
        $model = ActivityModel::where('type', $type)->where('id', $id)
            ->first();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        return $model;
    }

    public static function save(int $type, array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = ActivityModel::findOrNew($id);
        $model->load($data);
        $model->type = $type;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $type, int $id) {
        ActivityModel::where('id', $id)->where('type', $type)->delete();
    }
}