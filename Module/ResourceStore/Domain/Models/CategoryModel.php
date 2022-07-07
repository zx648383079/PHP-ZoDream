<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Models;

use Domain\Model\Model;

/**
 * Class CategoryModel
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $keywords
 * @property string $description
 * @property string $thumb
 * @property integer $is_hot
 */
class CategoryModel extends Model {
    public static function tableName() {
        return 'res_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,40',
            'parent_id' => 'int',
            'keywords' => 'string:0,255',
            'description' => 'string:0,255',
            'thumb' => 'string:0,255',
            'is_hot' => 'int:0,9'
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '分类名',
            'parent_id' => '三级',
            'keywords' => '关键词',
            'description' => '说明',
            'thumb' => '封面',
        ];
    }

    public function getThumbAttribute() {
        $thumb = $this->getAttributeSource('thumb');
        return url()->asset(empty($thumb) ? '/assets/images/banner.jpg' : $thumb);
    }
}