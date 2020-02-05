<?php
namespace Module\Family\Domain\Model;

use Domain\Model\Model;
/**
 * Class FamilyLogModel
 * @property integer $id
 * @property integer $family_id
 * @property string $relation_family
 * @property string $event
 * @property string $remark
 * @property integer $edit_user
 * @property integer $created_at
 */
class FamilyLogModel extends Model {

	public static function tableName() {
        return 'fy_family_log';
    }
    protected function rules() {
        return [
            'family_id' => 'required|int',
            'relation_family' => 'string:0,255',
            'event' => 'string:0,30',
            'remark' => '',
            'edit_user' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'family_id' => 'Family Id',
            'relation_family' => 'Relation Family',
            'event' => 'Event',
            'remark' => 'Remark',
            'edit_user' => 'Edit User',
            'created_at' => 'Created At',
        ];
    }

}