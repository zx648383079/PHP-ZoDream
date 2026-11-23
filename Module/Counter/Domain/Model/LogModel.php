<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;


/**
 * Class LogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $ip
 * @property string $user_agent
 * @property string $hostname
 * @property string $pathname
 * @property string $queries
 * @property string $referrer_hostname
 * @property string $referrer_pathname
 * @property string $method
 * @property integer $status_code
 * @property integer $user_id
 * @property string $session_id
 * @property string $latitude
 * @property string $longitude
 * @property integer $created_at
 */
class LogModel extends Model {

    public static function tableName(): string {
        return 'ctr_log';
    }

    protected function rules(): array {
        return [
            'ip' => 'required|string:0,120',
            'hostname' => 'string:0,255',
            'pathname' => 'string:0,255',
            'queries' => 'string:0,1000',
            'referrer_hostname' => 'string:0,255',
            'referrer_pathname' => 'string:0,255',
            'method' => 'string:0,10',
            'status_code' => 'int',
            'user_agent' => 'string:0,1000',
            'user_id' => 'int',
            'session_id' => 'string:0,32',
            'latitude' => 'string:0,30',
            'longitude' => 'string:0,30',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'hostname' => 'Hostname',
            'pathname' => 'Pathname',
            'queries' => 'Queries',
            'referrer_hostname' => 'Referrer Hostname',
            'referrer_pathname' => 'Referrer Pathname',
            'method' => 'Method',
            'status_code' => 'Status Code',
            'user_agent' => 'User Agent',
            'user_id' => 'User Id',
            'session_id' => 'Session Id',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'created_at' => 'Created At',
        ];
    }


}
