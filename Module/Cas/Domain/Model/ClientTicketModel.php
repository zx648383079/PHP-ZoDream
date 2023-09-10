<?php
namespace Module\Cas\Domain\Model;


use Domain\Model\Model;

/**
 * Class ClientTicketModel
 * @package Module\Cas\Domain\Model
 * @property integer $id
 * @property string $ticket
 * @property string $session_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ClientTicketModel extends Model {

    public static function tableName(): string {
        return 'cas_client_ticket';
    }

    protected function rules(): array {
        return [
            'ticket' => 'required|string:0,60',
            'session_id' => 'required|string:0,60',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'ticket' => 'Ticket',
            'session_id' => 'Session Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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