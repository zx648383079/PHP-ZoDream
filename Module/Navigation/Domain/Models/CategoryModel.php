<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property integer $parent_id
 */
class CategoryModel extends Model {
    public static function tableName() {
        return 'search_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'icon' => 'string:0,255',
            'parent_id' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'icon' => 'Icon',
            'parent_id' => 'Parent Id',
        ];
    }
}
