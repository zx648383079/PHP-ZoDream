<?php
namespace Module\Shop\Domain\Models;

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
    public static function tableName(): string {
        return 'shop_article_category';
    }

    public function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'description' => 'string:0,200',
            'parent_id' => 'int',
            'position' => 'int:0,999',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'keywords' => '关键字',
            'description' => '说明',
            'parent_id' => '上级',
            'position' => '排序',
        ];
    }
}