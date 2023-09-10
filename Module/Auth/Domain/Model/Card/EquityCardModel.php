<?php
namespace Module\Auth\Domain\Model\Card;


use Domain\Model\Model;

/**
 *
 * 有期限的权益卡
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property string $configure
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class EquityCardModel extends Model {

    public static function tableName(): string {
        return 'equity_card';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,32',
            'icon' => 'required|string:0,255',
            'configure' => 'string:0,200',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'icon' => 'Icon',
            'configure' => 'Configure',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}