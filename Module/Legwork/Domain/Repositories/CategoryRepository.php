<?php
declare(strict_types=1);
namespace Module\Legwork\Domain\Repositories;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Legwork\Domain\Model\CategoryModel;
use Module\Legwork\Domain\Model\CategoryProviderModel;

class CategoryRepository {
    public static function getList(string $keywords = '') {
        return CategoryModel::query()->when(!empty($keywords), function ($query) {
            CategoryModel::searchWhere($query, ['name']);
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

    public static function providerList(int $id, string $keywords = '', int $status = 0) {
        $links = CategoryProviderModel::where('cat_id', $id)
            ->when($status > 0, function ($query) {
                $query->where('status', 1);
            })->pluck(null, 'user_id');
        $page = UserSimpleModel::query()->when(!empty($keywords), function ($query) {
            UserSimpleModel::searchWhere($query, ['name']);
        })->whereIn('id', array_keys($links))->page();
        foreach ($page as $item) {
            $item['status'] = $links[$item['id']]['status'];
        }
        return $page;
    }

    public static function all() {
        return CategoryModel::query()->get();
    }
}