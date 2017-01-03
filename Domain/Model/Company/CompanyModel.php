<?php
namespace Domain\Model\Company;

use Domain\Model\Model;
/**
* Class CompanyModel
* @property integer $id
* @property string $name
* @property string $description
* @property string $charge
* @property string $phone
* @property string $address
* @property string $product
* @property string $demand
* @property string $url
* @property integer $user_id
* @property integer $status
* @property integer $create_at
* @property integer $update_at
*/
class CompanyModel extends Model {
	public static function tableName() {
        return 'company';
    }

    protected function rules() {
		return array (
		  'name' => 'string:3-200',
		  'description' => 'string:3-255',
		  'charge' => 'string:3-100',
		  'phone' => 'string:3-20',
		  'address' => 'string:3-255',
		  'product' => 'string:3-255',
		  'demand' => 'string:3-255',
		  'url' => 'string:3-100',
		  'user_id' => 'int',
		  'status' => 'int',
		  'create_at' => 'int',
		  'update_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'name' => 'Name',
		  'description' => 'Description',
		  'charge' => 'Charge',
		  'phone' => 'Phone',
		  'address' => 'Address',
		  'product' => 'Product',
		  'demand' => 'Demand',
		  'url' => 'Url',
		  'user_id' => 'User Id',
		  'status' => 'Status',
		  'create_at' => 'Create At',
		  'update_at' => 'Update At',
		);
	}
}