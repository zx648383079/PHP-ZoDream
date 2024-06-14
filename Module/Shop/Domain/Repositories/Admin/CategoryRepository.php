<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Html\Tree;

class CategoryRepository extends CRUDRepository {

    protected static function query(): SqlBuilder
    {
        return CategoryModel::query();
    }

    protected static function createNew(): Model
    {
        return new CategoryModel();
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
            static::query(),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function findOrNew(string $name) {
        if (empty($name)) {
            return 0;
        }
        $id = static::query()->where('name', $name)->value('id');
        if ($id > 0) {
            return $id;
        }
        return static::query()->insert([
            'name' => $name
        ]);
    }

    protected static function updateCache() {
        cache()->delete('shop_category_tree');
        cache()->delete('shop_category_level');
    }
}