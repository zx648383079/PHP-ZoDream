<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleModel;
use Zodream\Html\Tree;

class ArticleRepository {
    public static function getList(string $keywords = '', int|string $category = 0) {
        return ArticleModel::query()
            ->with('category')
            ->select(ArticleModel::THUMB_MODE)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->when(!empty($category), function ($query) use ($category) {
                if (is_numeric($category)) {
                    $query->where('cat_id', $category);
                    return;
                }
                $catId = ArticleCategoryModel::where('name', $category)
                    ->value('id');
                if (empty($catId)) {
                    $query->isEmpty();
                    return;
                }
                $query->where('cat_id', $catId);
            })->page();
    }

    public static function get(int $id) {
        return ArticleModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = ArticleModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        ArticleModel::where('id', $id)->delete();
    }

    public static function categoryList(string $keywords = '') {
        return ArticleCategoryModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function category(int $id) {
        return ArticleCategoryModel::findOrThrow($id, '数据有误');
    }

    public static function categorySave(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = ArticleCategoryModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function categoryRemove(int $id) {
        ArticleCategoryModel::where('id', $id)->delete();
        ArticleModel::where('cat_id', $id)->delete();
    }

    public static function categoryAll() {
        return (new Tree(ArticleCategoryModel::query()
            ->orderBy('position', 'asc')->get('id', 'name', 'parent_id')))->makeTreeForHtml();
    }
}