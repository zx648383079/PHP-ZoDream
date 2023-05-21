<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Advertisement\AdModel;
use Module\Shop\Domain\Models\Advertisement\AdPageModel;
use Module\Shop\Domain\Models\Advertisement\AdPositionModel;

class AdRepository {
    public static function getList(string $keywords = '', int|string $position = 0) {
        return AdPageModel::query()
            ->with('position')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->when(!empty($position), function ($query) use ($position) {
                if (is_numeric($position)) {
                    $query->where('position_id', $position);
                    return;
                }
                $positionId = AdPositionModel::where('name', $position)
                    ->value('id');
                if (empty($positionId)) {
                    $query->isEmpty();
                    return;
                }
                $query->where('position_id', $positionId);
            })->page();
    }

    public static function get(int $id) {
        return AdModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AdModel::findOrNew($id);
        $model->load($data);
        if ($model->type % 2 > 0 && isset($data['content_url'])) {
            $model->content = $data['content_url'];
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        AdModel::where('id', $id)->delete();
    }

    public static function positionList(string $keywords = '') {
        return AdPositionModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function position(int $id) {
        return AdPositionModel::findOrThrow($id, '数据有误');
    }

    public static function positionSave(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = AdPositionModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function positionRemove(int $id) {
        AdPositionModel::where('id', $id)->delete();
        AdModel::where('position_id', $id)->delete();
    }

    public static function positionAll() {
        return AdPositionModel::query()->get('id', 'name');
    }
}