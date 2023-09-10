<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;

/**
 * Class ForumModeratorModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $forum_id
 * @property integer $role_id
 */
class ForumModeratorModel extends Model {
    public static function tableName(): string {
        return 'bbs_forum_moderator';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'forum_id' => 'int',
            'role_id' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'forum_id' => 'Forum Id',
            'role_id' => 'Role Id',
        ];
    }

}