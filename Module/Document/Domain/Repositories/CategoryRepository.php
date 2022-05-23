<?php
declare(strict_types=1);
namespace Module\Document\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Module\OnlineTV\Domain\Models\CategoryModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Html\Tree;

final class CategoryRepository extends CRUDRepository {

    protected static function query(): SqlBuilder
    {
        return CategoryModel::query();
    }

    protected static function createNew(): Model
    {
        return new CategoryModel();
    }

    public static function toTree(bool $full = false) {
        return new Tree(CategoryModel::query()->when(!$full, function ($query) {
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
        return self::toTree(false)->makeIdTree();
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
}
