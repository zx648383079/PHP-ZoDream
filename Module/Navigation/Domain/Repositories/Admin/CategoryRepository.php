<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories\Admin;

use Domain\Model\Model;
use Domain\Repositories\CRUDRepository;
use Module\Navigation\Domain\Models\CategoryModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Html\Tree;

final class CategoryRepository extends CRUDRepository {

    public static function all() {
        return (new Tree(CategoryModel::query()->get()))->makeTreeForHtml();
    }

    protected static function query(): SqlBuilder
    {
        return CategoryModel::query();
    }

    protected static function createNew(): Model
    {
        return new CategoryModel();
    }
}
