<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;

/**
 * Class ThemePageModel
 * @package Module\Template\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property integer $theme_id
 * @property string $path
 */
class ThemePageModel extends Model {
    public static function tableName() {
        return 'tpl_theme_page';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'description' => 'string:0,200',
            'thumb' => 'string:0,100',
            'theme_id' => 'required|int',
            'path' => 'required|string:0,200',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'theme_id' => 'Theme Id',
            'path' => 'Path',
        ];
    }
}