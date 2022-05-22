<?php
declare(strict_types=1);
namespace Module\Video\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Video\Domain\Models\MusicModel;

class MusicRepository {

    public static function getList(string $keywords = '') {
        return MusicModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'singer']);
        })->page();
    }

    public static function get(int $id) {
        return MusicModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = MusicModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        MusicModel::where('id', $id)->delete();
    }
}