<?php
namespace Domain\Model;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Url\Url;
use Zodream\Infrastructure\Request;

/**
* Class LogModel
* @property integer $id
* @property string $ip
* @property string $url
* @property string $user
* @property string $event
* @property string $data
* @property integer $create_at
*/
class LogModel extends Model {
	public static function tableName() {
        return 'log';
    }

    protected function rules() {
		return array (
			'ip' => 'required|string:3-20',
            'url' => '|string:3-255',
			'user' => 'required|string:3-30',
			'event' => 'required|string:3-20',
			'data' => '',
			'create_at' => 'required|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'ip' => 'Ip',
		  'url' => 'Url',
		  'user' => 'User',
		  'event' => 'Event',
		  'data' => 'Data',
		  'create_at' => 'Create At',
		);
	}

	/**
	 * ADD NEW LOG
	 * @param $data
	 * @param $action
	 * @return mixed
	 */
	public static function addLog($data, $action) {
		return (new static)->add(array(
			'event' => $action,
			'data' => is_string($data) ? $data : json_encode($data),
			'url' => Url::to(),
			'ip' => Request::ip(),
			'create_at' => time(),
			'user' => Auth::guest() ? null : Auth::user()['name']
		));
	}

	/**
	 * 
	 * @param $action
	 * @param null $data
	 * @return bool|static
	 */
	public static function hasLog($action, $data = null) {
	    $where = [
	        'ip' => Request::ip()
        ];
		if (!Auth::guest()) {
		    $where['user'] = [Auth::user()['name'], 'or'];
		}
		$where['event'] = $action;
		if (!is_null($data)) {
		    $where['data'] = is_string($data) ? $data : json_encode($data);
		}
		return static::findOne($where);
	}
}