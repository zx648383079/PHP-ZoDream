<?php
namespace Module\Cas\Domain\Model;


use Domain\Model\Model;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Http\Http;
use Zodream\Http\Uri;

/**
 * Class TicketModel
 * @package Module\Cas\Domain\Model
 * @property integer $id
 * @property string $ticket
 * @property string $service_url
 * @property integer $service_id
 * @property integer $user_id
 * @property string $proxies
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class TicketModel extends Model {

    public static function tableName() {
        return 'cas_ticket';
    }

    protected function rules() {
        return [
            'ticket' => 'required|string:0,60',
            'service_url' => 'required|string:0,200',
            'service_id' => 'required|int',
            'user_id' => 'required|int',
            'proxies' => '',
            'expired_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'ticket' => 'Ticket',
            'service_url' => 'Service Url',
            'service_id' => 'Service Id',
            'user_id' => 'User Id',
            'proxies' => 'Proxies',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function isExpired() {
        return $this->expired_at < time();
    }

    public function isProxy() {
        return !empty($this->proxies);
    }


    public function getProxiesAttribute() {
        return empty($this->__attributes['proxies'])
            ? [] : Json::decode($this->__attributes['proxies'], true);
    }

    public function setProxiesAttribute($value) {
        //can not modify an existing record
        if ($this->id) {
            return;
        }
        $this->setAttributeSource('proxies', Json::decode($value));
    }

    public function generateTicket() {
        return static::generate(
            32,
            empty($this->proxies) ? 'ST-' : 'PT-',
            function ($ticket) {
                return is_null(static::getByTicket($ticket, false));
            },
            10
        );
    }

    /**
     * @param $ticket
     * @param bool $checkExpired
     * @return static
     */
    public static function getByTicket($ticket, $checkExpired = true) {
        $record = static::where('ticket', $ticket)->one();
        if (!$record) {
            return null;
        }
        return ($checkExpired && $record->isExpired()) ? null : $record;
    }

    /**
     * @return bool|null
     */
    public function invalidTicket() {
        return $this->delete();
    }

    /**
     * @param $service
     * @return static
     */
    public static function findByService($service) {
        return self::where('service_url', $service)->one();
    }

    /**
     * @param $user_id
     * @return static[]
     */
    public static function findByUser($user_id) {
        return self::where('user_id', $user_id)->all();
    }

    public function sendLogout() {
        $uri = new Uri($this->service_url);
        (new Http($uri->addPath('logout')))->get();
    }
}