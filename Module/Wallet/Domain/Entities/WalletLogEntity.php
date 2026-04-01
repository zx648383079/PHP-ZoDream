<?php
namespace Module\Wallet\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $money
 * @property integer $credits
 * @property string $hash
 * @property integer $created_at
 */
class WalletLogEntity extends Entity {
    public static function tableName(): string {
        return 'user_wallet_log';
    }

    protected function rules(): array {
		return [
            'user_id' => 'int',
            'money' => 'required|int',
            'credits' => 'required|int',
            'hash' => 'string:0,255',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'money' => 'Money',
            'credits' => 'Credits',
            'hash' => 'Hash',
            'created_at' => 'Created At',
        ];
	}

}