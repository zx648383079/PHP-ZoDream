<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Template\Module;
use Zodream\Disk\Directory;
use Zodream\Disk\FileObject;
use Zodream\Helpers\Json;

/**
 * Class ThemeModel
 * @package Module\Template\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property string $path
 * @property ThemeWeightModel[] $weights
 * @property ThemePageModel[] $pages
 * @property ThemeStyleModel[] $styles
 */
class ThemeModel extends Model {
    public static function tableName() {
        return 'tpl_theme';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'description' => 'string:0,200',
            'thumb' => 'string:0,100',
            'path' => 'required|string:0,200',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'path' => 'Path',
        ];
    }


}