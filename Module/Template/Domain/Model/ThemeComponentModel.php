<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Template\Domain\Entities\ThemeComponentEntity;
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
class ThemeComponentModel extends ThemeComponentEntity {


    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function category() {
        return $this->hasOne(ThemeCategoryModel::class, 'id', 'cat_id');
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