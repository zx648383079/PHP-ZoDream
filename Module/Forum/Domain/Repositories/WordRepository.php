<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Forum\Domain\Model\BlackWordModel;

class WordRepository {

    public static function getList(string $keywords = '') {
        return BlackWordModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['words', 'replace_words']);
        })->page();
    }

    public static function get(int $id) {
        return BlackWordModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = BlackWordModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        BlackWordModel::where('id', $id)->delete();
    }
}