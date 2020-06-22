<?php
namespace Module\Demo\Domain\Model;

use Domain\Model\Model;

/**
 * Class CategoryModel
 * @package Module\Demo\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $keywords
 * @property string $description
 * @property string $thumb
 */
class CategoryModel extends Model {
    public static function tableName() {
        return 'demo_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,40',
            'parent_id' => 'int',
            'keywords' => 'string:0,255',
            'description' => 'string:0,255',
            'thumb' => 'string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'parent_id' => 'Parent Id',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'thumb' => 'Thumb',
        ];
    }

    public function getThumbAttribute() {
        $thumb = $this->getAttributeSource('thumb');
        return url()->asset(empty($thumb) ? '/assets/images/banner.jpg' : $thumb);
    }

    public function post() {
        return $this->hasMany(PostModel::class, 'cat_id', 'id');
    }
}