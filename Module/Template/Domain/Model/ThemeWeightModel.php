<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Module\Template\Domain\VisualEditor\VisualPage;
use Module\Template\Module;
use Zodream\Disk\Directory;


/**
 * 安装的部件列表
 * @package Module\Template
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property integer $type
 * @property string $path
 * @property integer $editable
 * @property integer $theme_id
 * @property string $dependencies
 */
class ThemeWeightModel extends Model {

    const TYPE_BASIC = 0;
    const TYPE_ADVANCE = 1;

    const ADAPT_ALL = 0; // 自适应
    const ADAPT_PC = 1;  // 适应PC
    const ADAPT_MOBILE = 2; // 适应手机

    public static function tableName() {
        return 'tpl_theme_weight';
    }


    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'description' => 'string:0,200',
            'thumb' => 'string:0,100',
            'type' => 'int:0,127',
            'adapt_to' => 'int:0,9',
            'editable' => '',
            'theme_id' => 'required|int',
            'path' => 'string:0,200',
            'dependencies' => 'string'
        ];
    }

    protected function labels() {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'type' => 'Type',
            'editable' => 'Editable',
            'theme_id' => 'Theme Id',
            'path' => 'Path',
        ];
    }


    public function getDirectoryAttribute() {
        if (file_exists($this->path)) {
            return new Directory(is_dir($this->path)
                ? $this->path : dirname($this->path));
        }
        return VisualFactory::templateFolder($this->path);
    }

    public function getDependenciesAttribute() {
        return explode(',', $this->getAttributeSource('dependencies'));
    }

    public function setDependenciesAttribute($value) {
        $this->setAttributeSource('dependencies', implode(',', (array)$value));
    }
}