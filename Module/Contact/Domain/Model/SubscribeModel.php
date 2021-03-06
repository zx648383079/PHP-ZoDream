<?php
namespace Module\Contact\Domain\Model;


use Domain\Model\Model;

/**
 * Class SubscribeModel
 * @package Module\Contact\Domain\Model
 * @property integer $id
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class SubscribeModel extends Model {
    public static function tableName() {
        return 'cif_subscribe';
    }

    protected function rules() {
        return [
            'email' => 'required|string:0,50',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}