<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $sex
 * @property string $country
 * @property integer $type
 * @property string $card_no
 * @property string $expiry_date
 * @property string $profession
 * @property string $address
 * @property string $front_side
 * @property string $back_side
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class CertificationModel extends Model {

    public static function tableName(): string {
        return 'shop_certification';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,20',
            'sex' => 'string',
            'country' => 'string:0,20',
            'type' => 'int:0,127',
            'card_no' => 'required|string:0,30',
            'expiry_date' => 'string:0,30',
            'profession' => 'string:0,30',
            'address' => 'string:0,200',
            'front_side' => 'string:0,200',
            'back_side' => 'string:0,200',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'sex' => 'Sex',
            'country' => 'Country',
            'type' => 'Type',
            'card_no' => 'Card No',
            'expiry_date' => 'Expiry Date',
            'profession' => 'Profession',
            'address' => 'Address',
            'front_side' => 'Front Side',
            'back_side' => 'Back Side',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}