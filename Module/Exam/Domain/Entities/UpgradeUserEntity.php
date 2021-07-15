<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class UpgradeUserEntity
 * @package Module\Exam\Domain\Entities
 * @property integer $id
 * @property integer $upgrade_id
 * @property integer $user_id
 * @property integer $created_at
 */
class UpgradeUserEntity extends Entity {
    public static function tableName() {
        return 'exam_upgrade_user';
    }

    protected function rules() {
        return [
            'upgrade_id' => 'required|int',
            'user_id' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'upgrade_id' => 'Upgrade Id',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function upgrade() {
        return $this->hasOne(UpgradeEntity::class, 'id', 'upgrade_id');
    }
}