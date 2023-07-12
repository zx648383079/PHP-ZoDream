<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $mail_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class MailUserEntity extends Entity {
    public static function tableName() {
        return 'gm_mail_user';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'mail_id' => 'required|int',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'mail_id' => 'Mail Id',
            'user_id' => 'User Id',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}