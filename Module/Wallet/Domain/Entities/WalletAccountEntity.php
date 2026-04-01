<?php
namespace Module\Wallet\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $type
 * @property string $account
 * @property string $password
 * @property integer $expired_at
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class WalletAccountEntity extends Entity {
    public static function tableName(): string {
        return 'user_wallet_account';
    }

    protected function rules(): array {
		return [
            'type' => 'int:0,127',
            'account' => 'required|string:0,255',
            'password' => 'string:0,255',
            'expired_at' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'type' => 'Type',
            'account' => 'Account',
            'password' => 'Password',
            'expired_at' => 'Expired At',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}