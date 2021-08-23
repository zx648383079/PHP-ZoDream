<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories\Admin;

use Domain\Repositories\TagRepository as BaseTagRepository;
use Module\Navigation\Domain\Models\KeywordModel;
use Module\Navigation\Domain\Models\PageKeywordModel;
use Zodream\Database\Contracts\SqlBuilder;

final class KeywordRepository extends BaseTagRepository {

    protected static string $nameKey = 'word';

    protected static function query(): SqlBuilder
    {
        return KeywordModel::query();
    }



    public static function remove(int $id) {
        KeywordModel::where('id', $id)->delete();
        PageKeywordModel::where('word_id', $id)->delete();
    }
}
