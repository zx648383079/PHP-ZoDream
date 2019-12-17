<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Zodream\Infrastructure\Http\Request;

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

    public static function tableName() {
        return 'ctr_stay_time_log';
    }

    protected function rules() {
        return [
            'url' => 'required|string:0,255',
            'ip' => 'required|string:0,120',
            'user_agent' => 'string:0,255',
            'session_id' => 'string:0,30',
            'status' => 'int:0,9',
            'enter_at' => 'int',
            'leave_at' => 'int',
        ];
    }

    protected function labels() {
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

    public static function log(Request $request) {
        if ($request->has('loaded') && !$request->has('leave')) {
            return;
        }
        $data = [
            'url' => $request->uri(),
            'ip' => $request->ip(),
            'session_id' => session()->id(),
            'user_agent' => $request->server('HTTP_USER_AGENT', '-'),
        ];
        if (!$request->has('leave')) {
            static::create([
                'url' => (string)$data['url'],
                'ip' => $data['ip'],
                'user_agent' => $data['user_agent'],
                'session_id' => $data['session_id'],
                'status' => 0,
                'enter_at' => time(),
                'leave_at' => 0,
            ]);
            return;
        }
        static::where('url', $data['url'])
            ->where('session_id', $data['session_id'])
            ->where('leave_at', 0)->orderBy('id', 'desc')
            ->limit(1)
            ->update([
                'status' => 1,
                'leave_at' => time()
            ]);
    }
}
