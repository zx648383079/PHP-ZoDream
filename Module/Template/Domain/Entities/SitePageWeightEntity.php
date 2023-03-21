<?php
declare(strict_types=1);
namespace Module\Template\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $site_id
 * @property integer $weight_id
 * @property integer $parent_id
 * @property integer $parent_index
 * @property integer $position
 */
class SitePageWeightEntity extends Entity {
    public static function tableName() {
        return 'tpl_site_page_weight';
    }
    protected function rules() {
        return [
            'page_id' => 'required|int',
            'site_id' => 'required|int',
            'weight_id' => 'required|int',
            'parent_id' => 'int',
            'parent_index' => 'int:0,127',
            'position' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'page_id' => 'Page Id',
            'site_id' => 'Site Id',
            'weight_id' => 'Weight Id',
            'parent_id' => 'Parent Id',
            'parent_index' => 'Parent Index',
            'position' => 'Position',
        ];
    }
}