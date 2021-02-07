<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleSimpleModel;

class ArticleRepository {

    public static function getNotices() {
        return ArticleSimpleModel::query()->where('cat_id', 1)->get();
    }

    public static function getHelps(){
        $catId = 2;
        $data = ArticleCategoryModel::where('parent_id', $catId)->all();
        foreach ($data as $item) {
            $item->children = ArticleSimpleModel::where('cat_id', $item->id)->all();
        }
        $data = array_merge(ArticleSimpleModel::where('cat_id', $catId)->get(), $data);
        return $data;
    }
}