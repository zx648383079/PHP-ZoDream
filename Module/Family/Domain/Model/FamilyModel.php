<?php
namespace Module\Family\Domain\Model;

use Domain\Model\Model;
/**
 * Class FamilyModel
 * @property integer $id
 * @property string $name
 * @property string $secondary_name
 * @property integer $sex
 * @property string $birth_at
 * @property string $death_at
 * @property integer $parent_id
 * @property integer $mother_id
 * @property integer $spouse_id
 * @property integer $group_id
 * @property integer $level_id
 * @property string $lifetime
 * @property integer $clan_id
 * @property integer $status
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class FamilyModel extends Model {

	public static function tableName() {
        return 'fy_family';
    }
    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'secondary_name' => 'string:0,100',
            'sex' => 'int:0,127',
            'birth_at' => 'string:0,20',
            'death_at' => 'string:0,20',
            'parent_id' => 'int',
            'mother_id' => 'int',
            'spouse_id' => 'int',
            'clan_id' => 'int',
            'level_id' => 'int:0,99999',
            'lifetime' => '',
            'status' => 'int:0,127',
            'user_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '姓名',
            'secondary_name' => '表字',
            'sex' => '性别',
            'birth_at' => '生于',
            'death_at' => '卒于',
            'parent_id' => '生父',
            'mother_id' => '生母',
            'spouse_id' => '妻子',
            'clan_id' => '家族',
            'level_id' => '代',
            'lifetime' => '生平',
            'status' => '状态',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function clan() {
	    return $this->hasOne(ClanModel::class, 'id', 'clan_id');
    }

    public function saveIgnoreUpdate() {
        return $this->save() || $this->isNotChangedError();
    }



}