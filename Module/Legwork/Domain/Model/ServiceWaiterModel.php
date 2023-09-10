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

    const STATUS_NONE = 0;
    const STATUS_ALLOW = 1;
    const STATUS_DISALLOW = 2;

    public static function tableName(): string {
        return 'leg_service_waiter';
    }

    protected string $primaryKey = '';

    protected function rules(): array {
        return [
            'service_id' => 'required|int',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
        ];
    }

    protected function labels(): array {
        return [
            'service_id' => 'Service Id',
            'user_id' => 'User Id',
            'status' => 'Status',
        ];
    }
}