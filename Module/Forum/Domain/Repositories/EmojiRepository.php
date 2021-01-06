<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Module\Forum\Domain\Model\EmojiCategoryModel;
use Module\Forum\Domain\Model\EmojiModel;

class EmojiRepository {
    public static function getList(string $keywords = '', int $cat_id = 0) {
        return EmojiModel::query()->when(!empty($keywords), function ($query) {
            EmojiModel::searchWhere($query, ['name']);
        })->when($cat_id > 0, function ($query) use ($cat_id) {
            $query->where('cat_id', $cat_id);
        })->page();
    }

    public static function get(int $id) {
        return EmojiModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = EmojiModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        EmojiModel::where('id', $id)->delete();
    }

    public static function catList(string $keywords = '') {
        return EmojiCategoryModel::query()->when(!empty($keywords), function ($query) {
            EmojiCategoryModel::searchWhere($query, ['name']);
        })->get();
    }

    public static function getCategory(int $id) {
        return EmojiCategoryModel::findOrThrow($id, '数据有误');
    }

    public static function saveCategory(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = EmojiCategoryModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function removeCategory(int $id) {
        EmojiCategoryModel::where('id', $id)->delete();
    }
}