<?php
namespace Module\Game\CheckIn\Domain\Model;


use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class CheckInModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $running
 * @property integer $money
 * @property string $ip
 * @property integer $method
 * @property integer $created_at
 */
class CheckInModel extends Model {

    public bool $timestamps = false;

    public static function tableName(): string {
        return 'check_in';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'type' => 'int:0,9',
            'running' => 'int',
            'money' => 'int',
            'ip' => 'string:0,120',
            'method' => 'int:0,99',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'type' => 'Type',
            'running' => 'Running',
            'money' => 'Money',
            'ip' => 'Ip',
            'method' => 'Method',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function scopeMonth(mixed $query, int $time) {
        return $this->scopeTime($query, date('Y-m-01 00:00:00', $time), date('Y-m-31 23:59:59', $time));
    }

    public function scopeToday(mixed $query) {
        return $this->scopeTime($query, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
    }

    public function scopeYesterday(mixed $query) {
        $time = strtotime('-1 day');
        return $this->scopeTime($query, date('Y-m-d 00:00:00', $time), date('Y-m-d 23:59:59', $time));
    }

    public function scopeTime(mixed $query, string|int $start_at, string|int $end_at) {
        if (!is_numeric($start_at)) {
            $start_at = strtotime($start_at);
            $end_at = strtotime($end_at);
        }
        return $query->where('created_at', '>=', $start_at)->where('created_at', '<=', $end_at);
    }

}