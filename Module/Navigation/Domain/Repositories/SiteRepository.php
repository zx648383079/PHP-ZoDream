<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Navigation\Domain\Models\CategoryModel;
use Module\Navigation\Domain\Models\SiteModel;
use Module\Navigation\Domain\Models\SiteTagModel;
use Module\Navigation\Domain\Models\TagModel;
use Zodream\Html\Tree;

final class SiteRepository {

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
        $tagIds = SiteTagModel::where('site_id', $site)->pluck('tag_id');
        if (empty($tagIds)) {
            return [];
        }
        return TagModel::whereIn('id', $tagIds)->get();
    }

    public static function categories() {
        return (new Tree(CategoryModel::query()->orderBy('id', 'desc')->get()))->makeTree();
    }

    public static function recommend(int $category): array {
        return SiteModel::where('cat_id', $category)->where('top_type', '>', 0)
            ->orderBy('top_type', 'desc')->orderBy('id', 'asc')->get();
    }
}
