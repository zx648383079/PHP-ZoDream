<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Module\Counter\Domain\Events\CounterState;

/**
 * Class StayTimeLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $url
 * @property string $ip
 * @property string $user_agent
 * @property string $session_id
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
            'url' => 'required|string:0,255',
            'ip' => 'required|string:0,120',
            'user_agent' => 'string:0,255',
            'session_id' => 'string:0,32',
            'status' => 'int:0,9',
            'enter_at' => 'int',
            'leave_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'url' => 'Url',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
            'session_id' => 'Session Id',
            'status' => 'Status',
            'enter_at' => 'Enter At',
            'leave_at' => 'Leave At',
        ];
    }

    public static function log(CounterState $state) {
        if ($state->status === CounterState::STATUS_ENTER) {
            static::create([
                'url' => $state->url,
                'ip' => $state->ip,
                'user_agent' => $state->user_agent,
                'session_id' => $state->session_id,
                'status' => $state->status,
                'enter_at' => $state->getTimeOrNow('enter_at'),
                'leave_at' => 0,
            ]);
            return;
        }
        if ($state->status === CounterState::STATUE_LEAVE) {
            static::where('url', $state->url)
                ->where('session_id', $state->session_id)
                ->where('leave_at', 0)->orderBy('id', 'desc')
                ->limit(1)
                ->update([
                    'status' => $state->status,
                    'leave_at' => $state->getTimeOrNow('leave_at')
                ]);
            return;
        }
    }
}
