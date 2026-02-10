<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\TagProvider;
use Module\Navigation\Domain\Models\CategoryModel;
use Module\Navigation\Domain\Models\SiteModel;
use Zodream\Html\Tree;

final class SiteRepository {

    const BASE_KEY = 'search';

    public static function tag(): TagProvider {
        return new TagProvider(self::BASE_KEY);
    }

    public static function getList(string $keywords = '', int $category = 0, string $domain = '') {
        return SiteModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'domain']);
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when(!empty($domain), function ($query) use ($domain) {
                $query->where('domain', $domain);
            })->orderBy('score', 'desc')->page();
    }

    public static function get(int $id) {
        $model = SiteModel::findOrThrow($id);
        $data = $model->toArray();
        $data['tags'] = static::getTag($id);
        return $data;
    }

    public static function getTag(int $site): array {
        return self::tag()->getTags($site);
    }

    public static function categories() {
        return (new Tree(CategoryModel::query()->orderBy('id', 'desc')->get()))->makeTree();
    }

    public static function recommend(int $category): array {
        return SiteModel::where('cat_id', $category)->where('top_type', '>', 0)
            ->orderBy('top_type', 'desc')->orderBy('id', 'asc')->get();
    }

    public static function recommendGroup(int $category = 0): array {
        $catItems = CategoryModel::where('id', $category)
            ->orWhere('parent_id', $category)
            ->orderBy('parent_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        if (empty($catItems)) {
            return [];
        }
        $catId = [];
        foreach ($catItems as $item) {
            $catId[] = $item['id'];
        }
        $items = SiteModel::whereIn('cat_id', $catId)->where('top_type', '>', 0)
            ->orderBy('top_type', 'desc')->orderBy('id', 'asc')->get();
        if (empty($items)) {
            return [];
        }
        $data = [];
        foreach ($catItems as $cat) {
            $children = [];
            foreach ($items as $item) {
                if ($item['cat_id'] === $cat['id']) {
                    $children[] = $item;
                }
            }
            if (empty($children)) {
                continue;
            }
            $itemData = $cat->toArray();
            $itemData['items'] = $children;
            $data[] = $itemData;
        }
        return $data;
    }

    public static function submit(array $data) {
        
        return true;
    }
}
