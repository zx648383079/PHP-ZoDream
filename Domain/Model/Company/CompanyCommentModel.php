<?php
namespace Domain\Model\Company;

use Domain\Model\Model;
/**
* Class CompanyCommentModel
* @property integer $id
* @property string $title
* @property string $content
* @property string $image
* @property integer $star
* @property integer $user_id
* @property string $ip
* @property integer $status
* @property string $name
* @property integer $company_id
* @property integer $create_at
*/
class CompanyCommentModel extends Model {
	public static $table = 'company_comment';

	protected function rules() {
		return array (
		  'title' => 'required|string:3-100',
		  'content' => 'required',
		  'image' => '',
		  'star' => 'int',
		  'user_id' => 'int',
		  'ip' => 'string:3-20',
		  'status' => 'int',
		  'name' => 'string:3-45',
		  'company_id' => 'required|int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'title' => 'Title',
		  'content' => 'Content',
		  'image' => 'Image',
		  'star' => 'Star',
		  'user_id' => 'User Id',
		  'ip' => 'Ip',
		  'status' => 'Status',
		  'name' => 'Name',
		  'company_id' => 'Company Id',
		  'create_at' => 'Create At',
		);
	}
}