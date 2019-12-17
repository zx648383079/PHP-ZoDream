<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Zodream\Infrastructure\Http\Request;

/**
 * Class LoadTimeLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $url
 * @property string $ip
 * @property string $session_id
 * @property string $user_agent
 * @property integer $load_time
 * @property integer $created_at
 */
class LoadTimeLogModel extends Model {

    public static function tableName() {
        return 'ctr_load_time_log';
    }

    protected function rules() {
        return [
            'url' => 'required|string:0,255',
            'ip' => 'required|string:0,120',
            'session_id' => 'string:0,30',
            'user_agent' => 'string:0,255',
            'load_time' => 'required|int:0,99999',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'url' => 'Url',
            'ip' => 'Ip',
            'session_id' => 'Session Id',
            'user_agent' => 'User Agent',
            'load_time' => 'Load Time',
            'created_at' => 'Created At',
        ];
    }

    public static function log(Request $request) {
        if (!$request->has('loaded') || $request->has('leave')) {
            return;
        }
        static::create([
            'url' => (string)$request->uri(),
            'ip' => $request->ip(),
            'session_id' => session()->id(),
            'user_agent' => $request->server('HTTP_USER_AGENT', '-'),
            'load_time' => floatval(($request->get('loaded') - $request->get('enter')) / 1000),
        ]);
    }
}
