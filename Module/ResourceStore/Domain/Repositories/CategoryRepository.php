<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Module\ResourceStore\Domain\Models\CategoryModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Html\Tree;
use Zodream\Helpers\Tree as TreeHelper;

final class CategoryRepository extends CRUDRepository {

    protected static function query(): SqlBuilder
    {
        return CategoryModel::query();
    }

    protected static function createNew(): Model
    {
        return new CategoryModel();
    }

    public static function getChildren(int $parent = 0) {
        return static::query()->where('parent_id', $parent)->get();
    }


    public static function getFull(int $id) {
        $model = static::get($id);
        $data = $model->toArray();
        $data['children'] = static::getChildren($id);
        return $data;
    }

    public static function toTree(bool $full = false) {
        return new Tree(self::query()->when(!$full, function ($query) {
            $query->select('id', 'name', 'parent_id');
        })->get());
    }

    public static function levelTree(array $excludes = []) {
        $data = self::all(false);
        if (empty($excludes)) {
            return $data;
        }
        return array_filter($data, function ($item) use (&$excludes) {
            if (in_array($item['id'], $excludes)) {
                return false;
            }
            if (in_array($item['parent_id'], $excludes)) {
                $excludes[] = $item['id'];
                return false;
            }
            return true;
        });
    }

    public static function tree() {
        return self::toTree(false)->makeTree();
    }

    public static function all(bool $full = false) {
        return self::toTree($full)->makeTreeForHtml();
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            static::query(),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function recommend() {
        return CategoryModel::where('is_hot', 1)->get();
    }

    public static function getAllChildrenId(int $id) {
        $data = TreeHelper::getTreeChild(static::query()->get('id', 'parent_id'), $id);
        $data[] = $id;
        return $data;
    }
}