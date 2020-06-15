<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class BankCardModel
 * @package Module\Shop\Domain\Models
 * @property integer $id
 * @property integer $user_id
 * @property string $bank
 * @property integer $type
 * @property string $card_no
 * @property string $expiry_date
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class BankCardModel extends Model {

    public static function tableName() {
        return 'shop_bank_card';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'bank' => 'required|string:0,50',
            'type' => 'int:0,127',
            'card_no' => 'required|string:0,30',
            'expiry_date' => 'string:0,30',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'bank' => 'Bank',
            'type' => 'Type',
            'card_no' => 'Card No',
            'expiry_date' => 'Expiry Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}