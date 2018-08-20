<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class ArticleCategoryModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $description
 * @property integer $parent_id
 * @property integer $position
 */
class ArticleCategoryModel extends Model {
    public static function tableName() {
        return 'shop_article_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'description' => 'string:0,200',
            'parent_id' => 'int',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'parent_id' => 'Parent Id',
            'position' => 'Position',
        ];
    }
}