<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $position
 */
class CollectGroupModel extends Model {
    public static function tableName() {
        return 'search_collect_group';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'user_id' => 'int',
            'position' => 'int:0,127',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'user_id' => 'User Id',
            'position' => 'Position',
        ];
    }
}
