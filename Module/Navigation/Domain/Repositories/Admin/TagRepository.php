<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories\Admin;

use Domain\Repositories\TagRepository as BaseTagRepository;
use Module\Navigation\Domain\Models\SiteTagModel;
use Module\Navigation\Domain\Models\TagModel;
use Zodream\Database\Contracts\SqlBuilder;

final class TagRepository extends BaseTagRepository {

    protected static function query(): SqlBuilder
    {
        return TagModel::query();
    }


    public static function remove(int $id) {
        TagModel::where('id', $id)->delete();
        SiteTagModel::where('tag_id', $id)->delete();
    }
}
