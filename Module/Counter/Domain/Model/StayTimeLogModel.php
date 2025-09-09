<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * Class StayTimeLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property integer $log_id
 * @property integer $status
 * @property integer $enter_at
 * @property integer $leave_at
 */
class StayTimeLogModel extends Model {

    public static function tableName(): string {
        return 'ctr_stay_time_log';
    }

    protected function rules(): array {
        return [
            'log_id' => 'required|int',
            'status' => 'int:0,9',
            'enter_at' => 'int',
            'leave_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'log_id' => 'Log Id',
            'status' => 'Status',
            'enter_at' => 'Enter At',
            'leave_at' => 'Leave At',
        ];
    }

}
