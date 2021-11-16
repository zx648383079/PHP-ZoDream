<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Adapters;

use Domain\Model\SearchModel;
use Module\Navigation\Domain\Models\KeywordModel;
use Module\Navigation\Domain\Models\PageKeywordModel;
use Module\Navigation\Domain\Models\PageModel;
use Module\Navigation\Domain\Repositories\Admin\PageRepository;
use Zodream\Html\Page;

class Database implements ISearchAdapter {

    public function search(string $keywords): Page {
        $wordItems = $this->extractWord($keywords);
        if (empty($wordItems)) {
            return $this->emptyPage();
        }
        $pageIdItems = $this->computePageScore($wordItems);
        if (empty($pageIdItems)) {
            return $this->emptyPage();
        }
        $page = new Page($pageIdItems);
        $pageIdItems = $page->getPage();
        if (empty($pageIdItems)) {
            return $page;
        }
        $items = PageModel::with('site')->whereIn('id', array_column($pageIdItems, 'id'))
            ->orderBy('score', 'desc')->get();
        $page->setPage($this->mergeWord($items, $wordItems, $pageIdItems));
        return $page;
    }

    protected function mergeWord(array $items, array $wordItems, array $pageIdItems): array {
        $wordMap = [];
        foreach ($wordItems as $item) {
            $wordMap[$item['id']] = $item;
        }
        $wordLinkMap = [];
        foreach ($pageIdItems as $item) {
            $words = [];
            foreach ($item['words'] as $id) {
                $words[] = $wordMap[$id];
            }
            $wordLinkMap[$item['id']] = $words;
        }
        foreach ($items as &$item) {
            $item['keywords'] = $wordLinkMap[$item['id']];
        }
        unset($item);
        return $items;
    }

    protected function computePageScore(array $words): array {
        $baseScore = [];
        $wordIds = [];
        foreach ($words as $item) {
            $baseScore[$item['id']] = $item['type'] + 1;
            $wordIds[] = $item['id'];
        }
        $items = PageKeywordModel::whereIn('word_id', $wordIds)
            ->orderBy('is_official', 'desc')->get();
        if (empty($items)) {
            return [];
        }
        $data = [];
        foreach ($items as $item) {
            if (!isset($data[$item['page_id']])) {
                $data[$item['page_id']] = [
                    'id' => $item['page_id'],
                    'score' => 0,
                    'words' => [],
                ];
            }
            $data[$item['page_id']]['score'] += $baseScore[$item['word_id']] * 10 + $item['is_official'] * 10;
            $data[$item['page_id']]['words'][] = $item['word_id'];
        }
        $data = array_values($data);
        usort($data, function (array $a, array $b) {
            return $a['score'] <= $b['score'];
        });
        return $data;
    }

    protected function extractWord(string $keywords): array {
        $items = SearchModel::splitWord($keywords);
        if (empty($items)) {
            return [];
        }
        $wordItems = KeywordModel::whereIn('word', $items)->get();
        return array_map(function (KeywordModel $item) {
            return $item->toArray();
        }, $wordItems);
    }

    protected function emptyPage(): Page {
        return new Page(0);
    }

    public function get(int $id): array {
        $model = PageRepository::get($id);
        return $model->toArray();
    }

    public function create(array $data): array {
        $model = PageRepository::save($data);
        return $model->toArray();
    }

    public function update(int $id, array $data): void {
        $data['id'] = $id;
        PageRepository::save($data);
    }

    public function remove(int $id): void {
        PageRepository::remove($id);
    }


}