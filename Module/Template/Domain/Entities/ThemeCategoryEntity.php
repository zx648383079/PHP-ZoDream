<?php
declare(strict_types=1);
namespace Module\Template\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $description
 * @property string $thumb
 */
class ThemeCategoryEntity extends Entity {
    public static function tableName() {
        return 'tpl_theme_category';
    }
    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'parent_id' => 'int',
            'description' => 'string:0,255',
            'thumb' => 'string:0,100',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'parent_id' => 'Parent Id',
            'description' => 'Description',
            'thumb' => 'Thumb',
        ];
    }
}