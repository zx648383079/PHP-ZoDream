<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class InvoiceTitleModel
 * @package Module\Shop\Domain\Models
 * @property integer $id
 * @property integer $title_type
 * @property integer $type
 * @property string $title
 * @property string $tax_no
 * @property string $tel
 * @property string $bank
 * @property string $account
 * @property string $address
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class InvoiceTitleModel extends Model {

	public static function tableName() {
        return 'shop_invoice_title';
    }

    protected function rules() {
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
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}