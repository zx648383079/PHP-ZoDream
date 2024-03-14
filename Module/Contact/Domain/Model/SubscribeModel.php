<?php
namespace Module\Contact\Domain\Model;


use Domain\Model\Model;

/**
 * Class SubscribeModel
 * @package Module\Contact\Domain\Model
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class SubscribeModel extends Model {
    public static function tableName(): string {
        return 'cif_subscribe';
    }

    protected function rules(): array {
        return [
            'email' => 'required|string:0,50',
            'name' => 'string:0,30',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}