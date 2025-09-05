<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Module\Counter\Domain\Events\CounterState;

/**
 * Class LoadTimeLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property integer $log_id
 * @property integer $load_time
 * @property integer $created_at
 */
class LoadTimeLogModel extends Model {

    public static function tableName(): string {
        return 'ctr_load_time_log';
    }

    protected function rules(): array {
        return [
            'log_id' => 'required|int',
            'load_time' => 'required|int:0,99999',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'log_id' => 'Log Id',
            'load_time' => 'Load Time',
            'created_at' => 'Created At',
        ];
    }

    public static function log(CounterState $state) {
        if ($state !== CounterState::STATUS_LOADED) {
            return;
        }
        static::create([
            'url' => $state->url,
            'ip' => $state->ip,
            'session_id' => $state->session_id,
            'user_agent' => $state->session_id,
            'load_time' => $state->getLoadTime(),
        ]);
    }
}
