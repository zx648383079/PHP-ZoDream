<?php
namespace Module\Shop\Domain\Repositories;

use Exception;
use Module\Shop\Domain\Models\ArticleCategoryModel;
use Module\Shop\Domain\Models\ArticleSimpleModel;

class ArticleRepository {

    public static function getNotices() {
        return ArticleSimpleModel::query()->where('cat_id', 1)->get();
    }

    public static function getHelps(){
        $data = ArticleCategoryModel::where('parent_id', 2)->all();
        foreach ($data as $item) {
            $item->children = ArticleSimpleModel::where('cat_id', $item->id)->all();
        }
        return $data;
    }
}