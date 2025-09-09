<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * Class VisitorLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property integer $first_at
 * @property integer $last_at
 */
class VisitorLogModel extends Model {

    public static function tableName(): string {
        return 'ctr_visitor_log';
    }

    protected function rules(): array {
        return [
            'user_id' => 'int',
            'ip' => 'required|string:0,120',
            'first_at' => 'int',
            'last_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'ip' => 'Ip',
            'first_at' => 'First At',
            'last_at' => 'Last At',
        ];
    }

}
