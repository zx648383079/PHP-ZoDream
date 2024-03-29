<?php
namespace Module\Auth\Domain\Model\Card;


use Domain\Model\Model;

/**
 *
 * 用户的权益卡
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property integer $expired_at
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $card_id
 * @property integer $exp
 */
class UserEquityCardModel extends Model {

    public static function tableName(): string {
        return 'user_equity_card';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'status' => 'int:0,127',
            'expired_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
            'card_id' => 'required|int',
            'exp' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'status' => 'Status',
            'expired_at' => 'Expired At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'card_id' => 'Card Id',
            'exp' => 'Exp',
        ];
    }

    public function card() {
        return $this->hasOne(EquityCardModel::class, 'id', 'card_id');
    }
}