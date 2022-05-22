<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Repositories;

use Domain\Model\Model;
use Domain\Repositories\CRUDRepository;
use Module\ResourceStore\Domain\Models\CategoryModel;
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


    public static function toTree() {
        return new Tree(self::query()->get());
    }

    public static function levelTree(array $excludes = []) {
        $data = self::toTree()->makeTreeForHtml();
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
        return self::toTree()->makeIdTree();
    }


}