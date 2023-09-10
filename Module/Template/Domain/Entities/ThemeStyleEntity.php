<?php
declare(strict_types=1);
namespace Module\Template\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $component_id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property string $path
 */
class ThemeStyleEntity extends Entity {
    public static function tableName(): string {
        return 'tpl_theme_style';
    }

    protected function rules(): array {
        return [
            'component_id' => 'required|int',
            'name' => 'required|string:0,30',
            'description' => 'string:0,200',
            'thumb' => 'string:0,100',
            'path' => 'required|string:0,200',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'component_id' => 'Component Id',
            'name' => 'Name',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'path' => 'Path',
        ];
    }
}