<?php
namespace Module\Family\Domain\Model;

use Domain\Model\Model;
/**
 * Class FamilySpouseModel
 * @property integer $id
 * @property integer $family_id
 * @property integer $spouse_id
 * @property integer $relation
 * @property integer $status
 * @property string $start_at
 * @property string $end_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class FamilySpouseModel extends Model {

	public static function tableName(): string {
        return 'fy_family_spouse';
    }
    protected function rules(): array {
        return [
            'family_id' => 'required|int',
            'spouse_id' => 'required|int',
            'relation' => 'int:0,127',
            'status' => 'int:0,127',
            'start_at' => 'string:0,20',
            'end_at' => 'string:0,20',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'family_id' => 'Family Id',
            'spouse_id' => 'Spouse Id',
            'relation' => 'Relation',
            'status' => 'Status',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function spouse() {
	    return $this->hasOne(FamilyModel::class, 'id', 'spouse_id');
    }

}