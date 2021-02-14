<?php
namespace Module\Legwork\Domain\Model;


use Domain\Model\Model;

/**
 * Class ServiceWaiterModel
 * @package Module\Legwork\Domain\Model
 * @property integer $service_id
 * @property integer $user_id
 * @property integer $status
 */
class ServiceWaiterModel extends Model {
    public static function tableName() {
        return 'leg_service_waiter';
    }

    protected $primaryKey = '';

    protected function rules() {
        return [
            'service_id' => 'required|int',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
        ];
    }

    protected function labels() {
        return [
            'service_id' => 'Service Id',
            'user_id' => 'User Id',
            'status' => 'Status',
        ];
    }
}