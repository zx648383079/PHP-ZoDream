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
class ArticleSimpleModel extends ArticleEntity {

    protected array $append = ['category'];

    const THUMB_MODE = ['id', 'title', 'thumb', 'cat_id', 'created_at', 'updated_at'];

    public function category() {
        return $this->hasOne(ArticleCategoryModel::class, 'id', 'cat_id');
    }

    public static function query() {
        return parent::query()->select(self::THUMB_MODE);
    }
}