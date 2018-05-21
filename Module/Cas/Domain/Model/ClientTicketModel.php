<?php
namespace Module\Cas\Domain\Model;


use Domain\Model\Model;

class ClientTicketModel extends Model {

    public static function tableName() {
        return 'client_ticket';
    }

    /**
     *
     * @param $ticket
     * @return static
     */
    public function findByTicket($ticket) {
        return self::where('ticket', $ticket)->one();
    }
}