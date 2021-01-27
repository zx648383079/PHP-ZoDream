<?php
namespace Module\Shop\Domain\Models;

use Module\Shop\Domain\Entities\ArticleEntity;

/**
 * Class ArticleModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property integer $cat_id
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $brief
 * @property string $url
 * @property string $file
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class ArticleModel extends ArticleEntity {

    protected array $append = ['category'];

    const THUMB_MODE = ['id', 'title', 'description', 'thumb', 'cat_id', 'created_at', 'updated_at'];

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