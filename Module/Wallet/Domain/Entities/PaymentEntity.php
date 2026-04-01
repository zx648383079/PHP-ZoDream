<?php
namespace Module\Wallet\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $icon
 * @property string $description
 * @property string $settings
 */
class PaymentEntity extends Entity {
    public static function tableName(): string {
        return 'trade_payment';
    }

    protected function rules(): array {
		return [
            'name' => 'required|string:0,255',
            'code' => 'required|string:0,255',
            'icon' => 'string:0,255',
            'description' => 'string:0,255',
            'settings' => 'string:0,255',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'name' => 'Name',
            'code' => 'Code',
            'icon' => 'Icon',
            'description' => 'Description',
            'settings' => 'Settings',
        ];
	}
}