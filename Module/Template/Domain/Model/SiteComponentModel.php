<?php
namespace Module\Template\Domain\Model;

use Module\Template\Domain\Entities\SiteComponentEntity;

/**
 *
 * @property integer $id
 * @property integer $component_id
 * @property integer $site_id
 * @property integer $cat_id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property integer $type
 * @property string $author
 * @property string $version
 * @property integer $editable
 * @property string $alias_name
 * @property string $dependencies
 * @property string $path
 * @property integer $updated_at
 * @property integer $created_at
 */
class SiteComponentModel extends SiteComponentEntity {

    public function category() {
        return $this->hasOne(ThemeCategoryModel::class, 'id', 'cat_id');
    }

    public function getDependenciesAttribute() {
        $val = $this->getAttributeSource('dependencies');
        return empty($val) ? [] : explode("\n", $val);
    }

    public function setDependenciesAttribute($value) {
        $this->setAttributeSource('dependencies', is_array($value) ? implode("\n", $value) : $value);
    }
}