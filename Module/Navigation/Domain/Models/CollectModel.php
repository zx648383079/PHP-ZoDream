<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $link
 * @property integer $group_id
 * @property integer $user_id
 * @property integer $position
 * @property integer $updated_at
 * @property integer $created_at
 */
class CollectModel extends Model {
    public static function tableName() {
        return 'search_collect';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'link' => 'required|string:0,255',
            'group_id' => 'int',
            'user_id' => 'int',
            'position' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'link' => 'Link',
            'group_id' => 'Group Id',
            'user_id' => 'User Id',
            'position' => 'Position',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
