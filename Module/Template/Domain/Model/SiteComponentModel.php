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
 * @property string $path
 * @property integer $updated_at
 * @property integer $created_at
 */
class SiteComponentModel extends SiteComponentEntity {

    public function category() {
        return $this->hasOne(ThemeCategoryModel::class, 'id', 'cat_id');
    }
}