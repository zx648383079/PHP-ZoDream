<?php
namespace Module\Cas\Domain\Model;


use Domain\Model\Model;
use Zodream\Helpers\Str;
use Zodream\Http\Http;
use Zodream\Http\Uri;

/**
 * Class TicketModel
 * @package Module\Cas\Domain\Model
 * @property integer $id
 * @property string $ticket
 * @property string $service
 * @property integer $user_id
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
            'service' => 'required|string:0,200',
            'user_id' => 'required|int',
            'expired_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'ticket' => 'Ticket',
            'service' => 'Service',
            'user_id' => 'User Id',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function generateTicket() {
        return bin2hex(Str::randomBytes(20));
    }

    /**
     * @param $service
     * @return static
     */
    public static function findByService($service) {
        return self::where('service', $service)->one();
    }

    /**
     * @param $user_id
     * @return static[]
     */
    public static function findByUser($user_id) {
        return self::where('user_id', $user_id)->all();
    }

    public function sendLogout() {
        $uri = new Uri($this->service);
        (new Http($uri->addPath('logout')))->get();
    }
}