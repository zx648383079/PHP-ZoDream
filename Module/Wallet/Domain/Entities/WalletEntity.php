<?php
namespace Module\Wallet\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
* @property integer $id
 * @property integer $money
 * @property integer $credits
 * @property string $password
 * @property integer $updated_at
 * @property integer $created_at
 */
class WalletEntity extends Entity {
    public static function tableName(): string {
        return 'user_wallet';
    }

    protected function rules(): array {
		return [
            'money' => 'required|int',
            'credits' => 'required|int',
            'password' => 'required|string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'money' => 'Money',
            'credits' => 'Credits',
            'password' => 'Password',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}