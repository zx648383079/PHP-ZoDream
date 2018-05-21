<?php
namespace Module\Cas\Domain\Model;


use Domain\Model\Model;
use Zodream\Helpers\Str;
use Zodream\Http\Http;
use Zodream\Http\Uri;

class TicketModel extends Model {

    public static function tableName() {
        return 'cas_ticket';
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