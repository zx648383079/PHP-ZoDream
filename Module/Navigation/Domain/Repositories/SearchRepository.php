<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Navigation\Domain\Adapters\Database;
use Module\Navigation\Domain\Models\KeywordModel;
use Module\Navigation\Domain\Models\PageKeywordModel;
use Module\Navigation\Domain\Models\PageModel;
use Zodream\Html\Page;

final class SearchRepository {
    public static function getList(string $keywords = '') {
        return (new Database())->search($keywords);
    }

    public static function suggest(string $keywords = '') {
        return KeywordModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'word');
        })->orderBy('type', 'desc')->limit(10)->pluck('word');
    }
}
