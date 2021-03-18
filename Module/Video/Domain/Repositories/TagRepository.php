<?php
declare(strict_types=1);
namespace Module\Video\Domain\Repositories;

use Module\Video\Domain\Models\TagModel;
use Domain\Repositories\TagRepository as TagBase;
use Zodream\Database\Contracts\SqlBuilder;

class TagRepository extends TagBase {

    protected static function query(): SqlBuilder
    {
        return TagModel::query();
    }

    public static function remove(int $id) {
        TagModel::where('id', $id)->delete();
    }

}