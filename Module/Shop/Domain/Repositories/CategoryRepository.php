<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\CategoryModel;

class CategoryRepository {

    public static function getHomeFloor() {
        $categories_tree = CategoryModel::cacheTree();
        $floor_categories = [];
        foreach ($categories_tree as $item) {
            $item['children'] = isset($item['children']) ? array_splice($item['children'], 0, 4) : [];
            if (!empty($item['children'])) {
                $item['children'] = array_values($item['children']);
            }
            $item['goods_list'] = GoodsRepository::getRecommendQuery('is_hot')
                ->whereIn('cat_id', CategoryModel::getChildrenWithParent($item['id']))
                ->limit(4)->all();
            $item['url'] = url('./category', ['id' => $item['id']]);
            $floor_categories[] = $item;
        }
        return $floor_categories;
    }

    public static function findOrNew($name) {
        if (empty($name)) {
            return 0;
        }
        $id = CategoryModel::query()->where('name', $name)->value('id');
        if ($id > 0) {
            return $id;
        }
        return CategoryModel::query()->insert([
            'name' => $name
        ]);
    }

    public static function levelTree() {
        return CategoryModel::cacheLevel();
    }

    public static function tree() {
        return CategoryModel::cacheTree();
    }

    public static function getList(int $parent = 0) {
        return CategoryModel::where('parent_id', $parent)->get();
    }
}