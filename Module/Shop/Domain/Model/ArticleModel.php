<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

class ArticleModel extends Model {
    public static function tableName() {
        return 'article';
    }

    public function category() {
        return $this->hasOne(ArticleCategoryModel::class, 'id', 'cat_id');
    }

    public static function getHelps(){
        $data = ArticleCategoryModel::where('parent_id', 2)->all();
        foreach ($data as $item) {
            $item->children = static::where('cat_id', $item->id)->all();
        }
        return $data;
    }
}