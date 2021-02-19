<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookAuthorModel;

class AuthorRepository {
    public static function getList(string $keywords = '') {
        return BookAuthorModel::query()->when(!empty($keywords), function ($query) {
            BookAuthorModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return BookAuthorModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = BookAuthorModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        BookAuthorModel::where('id', $id)->delete();
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        $perPage = max(20, is_array($id) ? count($id) : 20);
        return BookAuthorModel::query()->when(!empty($keywords), function ($query) {
            BookAuthorModel::searchWhere($query, ['name']);
        })->limit($perPage)->get(['id', 'name', 'avatar']);
    }
}