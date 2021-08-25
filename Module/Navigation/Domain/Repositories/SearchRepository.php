<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Navigation\Domain\Models\KeywordModel;
use Module\Navigation\Domain\Models\PageKeywordModel;
use Module\Navigation\Domain\Models\PageModel;
use Zodream\Html\Page;

final class SearchRepository {
    public static function getList(string $keywords = '') {
        $items = SearchModel::splitWord($keywords);
        if (empty($items)) {
            return static::renderPage();
        }
        $wordId = KeywordModel::whereIn('word', $items)->pluck('id');
        if (empty($wordId)) {
            return static::renderPage();
        }
        $ids = PageKeywordModel::whereIn('word_id', $wordId)->orderBy('is_official', 'desc')
            ->pluck('page_id');
        if (empty($ids)) {
            return static::renderPage();
        }
        $page = new Page($ids);
        $items = $page->getPage();
        if (empty($items)) {
            return $page;
        }
        $page->setPage(PageModel::whereIn('id', $items)->get());
        return $page;
    }

    public static function renderPage(int $total = 0, array $pageId = []): Page {
        $page = new Page($total);
        if (!empty($pageId)) {
            $page->setPage(PageModel::whereIn('id', $pageId)->get());
        }
        return $page;
    }

    public static function suggest(string $keywords = '') {
        return KeywordModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'word');
        })->orderBy('type', 'desc')->limit(10)->pluck('word');
    }
}
