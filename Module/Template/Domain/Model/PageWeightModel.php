<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;


/**
 * Class PageWeightModel
 * @package Module\Template
 * @property integer $id
 * @property integer $page_id
 * @property integer $site_id
 * @property integer $weight_id
 * @property integer $parent_id
 * @property integer $parent_index
 * @property integer $position
 *
 */
class PageWeightModel extends Model {

    public static function tableName() {
        return 'tpl_page_weight';
    }

    protected function rules() {
        return [
            'page_id' => 'required|int',
            'site_id' => 'required|int',
            'weight_id' => 'required|int',
            'parent_id' => 'int',
            'parent_index' => 'int',
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
            'parent_index' => 'Parent index',
            'position' => 'Position',
        ];
    }

}