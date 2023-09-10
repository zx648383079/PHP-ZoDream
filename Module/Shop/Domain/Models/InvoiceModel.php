<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class InvoiceModel
 * @package Module\Shop\Domain\Models
 * @property integer $title_type
 * @property integer $type
 * @property string $title
 * @property string $tax_no
 * @property string $tel
 * @property string $bank
 * @property string $account
 * @property string $address
 * @property integer $user_id
 * @property float $money
 * @property integer $status
 * @property integer $invoice_type
 * @property string $receiver_email
 * @property string $receiver_name
 * @property string $receiver_tel
 * @property integer $receiver_region_id
 * @property string $receiver_region_name
 * @property string $receiver_address
 * @property integer $created_at
 * @property integer $updated_at
 */
class InvoiceModel extends Model {

	public static function tableName(): string {
        return 'shop_invoice';
    }

    public function rules(): array {
        return [
            'title_type' => 'int:0,9',
            'type' => 'int:0,9',
            'title' => 'required|string:0,100',
            'tax_no' => 'required|string:0,20',
            'tel' => 'required|string:0,11',
            'bank' => 'required|string:0,100',
            'account' => 'required|string:0,60',
            'address' => 'required|string:0,255',
            'user_id' => 'required|int',
            'money' => '',
            'status' => 'int:0,9',
            'invoice_type' => 'int:0,9',
            'receiver_email' => 'string:0,100',
            'receiver_name' => 'string:0,100',
            'receiver_tel' => 'string:0,100',
            'receiver_region_id' => 'int',
            'receiver_region_name' => 'string:0,255',
            'receiver_address' => 'string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'title_type' => 'Title Type',
            'type' => 'Type',
            'title' => 'Title',
            'tax_no' => 'Tax No',
            'tel' => 'Tel',
            'bank' => 'Bank',
            'account' => 'Account',
            'address' => 'Address',
            'user_id' => 'User Id',
            'money' => 'Money',
            'status' => 'Status',
            'invoice_type' => 'Invoice Type',
            'receiver_email' => 'Receiver Email',
            'receiver_name' => 'Receiver Name',
            'receiver_tel' => 'Receiver Tel',
            'receiver_region_id' => 'Receiver Region Id',
            'receiver_region_name' => 'Receiver Region Name',
            'receiver_address' => 'Receiver Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}