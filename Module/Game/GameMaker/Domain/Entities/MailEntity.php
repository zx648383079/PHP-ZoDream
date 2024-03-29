<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $content
 * @property string $gift
 * @property integer $expired_at
 * @property integer $created_at
 */
class MailEntity extends Entity {
    public static function tableName(): string {
        return 'gm_mail';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'content' => 'required',
            'gift' => 'string:0,255',
            'expired_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'content' => 'Content',
            'gift' => 'Gift',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
        ];
    }
}