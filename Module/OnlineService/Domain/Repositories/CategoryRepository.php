<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\OnlineService\Domain\Models\CategoryModel;
use Module\OnlineService\Domain\Models\CategoryUserModel;
use Module\OnlineService\Domain\Models\CategoryWordModel;

class CategoryRepository {
    public static function getList(string $keywords = '') {
        return CategoryModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return CategoryModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = CategoryModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        CategoryModel::where('id', $id)->delete();
    }

    public static function all() {
        return CategoryModel::query()->get();
    }

    public static function userList(int $category, string $keywords = '') {
        $userIds = CategoryUserModel::where('cat_id', $category)
            ->pluck('user_id');
        return UserSimpleModel::whereIn('id', $userIds)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query);
            })->page();
    }

    public static function userAdd(int $category, int|array $user) {
        $userIds = CategoryUserModel::where('cat_id', $category)
            ->pluck('user_id');
        $data = [];
        $exit = [];
        foreach ((array)$user as $item) {
            if (in_array($item, $userIds) || in_array($item, $exit) || $item < 1) {
                continue;
            }
            $data[] = [
                'cat_id' => $category,
                'user_id' => $item,
            ];
            $exit[] = $item;
        }
        if (empty($data)) {
            throw new \Exception('客服已存在');
        }
        CategoryUserModel::query()->insert($data);
    }

    public static function userRemove(int $category, int|array $user) {
        CategoryUserModel::where('cat_id', $category)
            ->whereIn('user_id', (array)$user)->delete();
    }

    public static function wordList(int $category = 0, string $keywords = '') {
        return CategoryWordModel::when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query);
        })->page();
    }

    public static function wordSave(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = CategoryWordModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function wordRemove(int $id) {
        CategoryWordModel::where('id', $id)->delete();
    }
}