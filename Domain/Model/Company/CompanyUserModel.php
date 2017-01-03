<?php
namespace Domain\Model\Company;

use Domain\Model\Model;
/**
* Class CompanyUserModel
* @property integer $company_id
* @property integer $user_id
* @property string $post
*/
class CompanyUserModel extends Model {
	public static function tableName() {
        return 'company_user';
    }

	protected function rules() {
		return array (
		  'company_id' => 'required|int',
		  'user_id' => 'required|int',
		  'post' => 'string:3-100',
		);
	}

	protected function labels() {
		return array (
		  'company_id' => 'Company Id',
		  'user_id' => 'User Id',
		  'post' => 'Post',
		);
	}
}