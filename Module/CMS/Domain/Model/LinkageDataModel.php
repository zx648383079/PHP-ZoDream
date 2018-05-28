<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;

/**
 * Class LinkageModel
 * @package Module\CMS\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $position
 */
class LinkageDataModel extends Model {
    public static function tableName() {
        return 'cms_linkage_data';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'parent_id' => 'int',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'parent_id' => 'Parent Id',
            'position' => 'Position',
        ];
    }
}