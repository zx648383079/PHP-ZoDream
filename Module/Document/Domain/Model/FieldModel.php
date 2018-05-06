<?php
namespace Module\Document\Domain\Model;


use Domain\Model\Model;

/**
 * Class FieldModel
 * @package Module\Document\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $is_required
 * @property string $default_value
 * @property string $mock
 * @property integer $parent_id
 * @property integer $api_id
 * @property integer $kind
 * @property string $type
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class FieldModel extends Model {

    const KIND_REQUEST = 1;
    const KIND_RESPONSE = 2;
    const KIND_HEADER = 3;

    public static function tableName() {
        return 'doc_field';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,50',
            'title' => 'required|string:0,50',
            'is_required' => 'int:0,9',
            'default_value' => 'string:0,255',
            'mock' => 'string:0,255',
            'parent_id' => 'int',
            'api_id' => 'required|int',
            'kind' => 'int:0,999',
            'type' => 'string:0,10',
            'remark' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'title' => 'Title',
            'is_required' => 'Is Required',
            'default_value' => 'Default Value',
            'mock' => 'Mock',
            'parent_id' => 'Parent Id',
            'api_id' => 'Api Id',
            'kind' => 'Kind',
            'type' => 'Type',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}