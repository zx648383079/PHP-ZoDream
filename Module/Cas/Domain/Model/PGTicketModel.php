<?php
namespace Module\Cas\Domain\Model;

use Zodream\Http\Http;
use Zodream\Http\Uri;

/**
 * Class PGTicketModel
 * @package Module\Cas\Domain\Model
 * @property integer $id
 * @property string $ticket
 * @property string $pgt_url
 * @property integer $service_id
 * @property integer $user_id
 * @property string $proxies
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class PGTicketModel extends BaseModel {

    public static function tableName(): string {
        return 'cas_proxy_granting_tickets';
    }

    protected function rules(): array {
        return [
            'ticket' => 'required|string:0,60',
            'pgt_url' => 'required|string:0,200',
            'service_id' => 'required|int',
            'user_id' => 'required|int',
            'proxies' => '',
            'expired_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'ticket' => 'Ticket',
            'pgt_url' => 'Pgt Url',
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

    public function getProxiesAttribute() {
        return json_decode($this->getAttributeSource('proxies'), true);
    }

    public function setProxiesAttribute($value) {
        //can not modify an existing record
        if ($this->id) {
            return;
        }
        $this->setAttributeSource('proxies', json_encode($value));
    }

    public static function getByTicket($ticket, $checkExpired = true) {
        $record = static::where('ticket', $ticket)->one();
        if (!$record) {
            return null;
        }
        return ($checkExpired && $record->isExpired()) ? null : $record;
    }

    public static function invalidTicketByUser($user_id) {
        static::where('user_id', $user_id)->delete();
    }

    public static function generateTicket() {
        return static::generate(
            64,
            'PGT-',
            function ($ticket) {
                return is_null(static::getByTicket($ticket, false));
            },
            10
        );
    }

    public function call(Uri $pgtUrl, $pgtiou) {
        $query = [
            'pgtId'  => $this->ticket,
            'pgtIou' => $pgtiou,
        ];
        $new_uri = clone $pgtUrl;
        $http = new Http($new_uri->addData($query));
        $http->get();
        return $http->getStatusCode() == 200;
    }
}