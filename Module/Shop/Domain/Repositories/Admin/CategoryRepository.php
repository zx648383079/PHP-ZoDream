<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Html\Tree;

class CategoryRepository {
    public static function getList(string $keywords = '') {
        return CategoryModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function get(int $id) {
        return CategoryModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
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

    public static function refresh() {
        CategoryModel::refreshPk(function ($old_id, $new_id) {
            CategoryModel::where('parent_id', $old_id)->update([
                'parent_id' => $new_id
            ]);
            GoodsModel::where('cat_id', $old_id)->update([
                'cat_id' => $new_id
            ]);
        });
    }

    public static function all(bool $full = false) {
        return (new Tree(CategoryModel::query()->when(!$full, function ($query) {
            $query->select('id', 'name', 'parent_id');
        })->get()))
            ->makeTreeForHtml();
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            CategoryModel::query(),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function findOrNew(string $name) {
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
}