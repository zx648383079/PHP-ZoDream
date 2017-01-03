<?php
namespace Domain\Model;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;

/**
* Class FeedbackModel
* @property integer $id
* @property string $name
* @property string $email
* @property string $phone
* @property string $content
* @property integer $user_id
* @property integer $read
* @property string $ip
* @property integer $create_at
*/
class FeedbackModel extends Model {
	public static function tableName() {
        return 'feedback';
    }

    protected function rules() {
		return array (
		  'name' => 'required|string:3-45',
		  'email' => '|string:3-100',
		  'phone' => '|string:3-45',
		  'content' => 'required',
		  'user_id' => '|int',
		  'read' => '|int:0-1',
		  'ip' => '|string:3-20',
		  'create_at' => '|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'name' => 'Name',
		  'email' => 'Email',
		  'phone' => 'Phone',
		  'content' => 'Content',
		  'user_id' => 'User Id',
		  'read' => 'Read',
		  'ip' => 'Ip',
		  'create_at' => 'Create At',
		);
	}
	
	protected function behaviors() {
		return [
			self::BEFORE_INSERT => function ($model) {
				/** @var $model FeedbackModel */
				if (!Auth::guest()) {
					$model->user_id = Auth::user()->getId();
				}
				$model->ip = Request::ip();
				$model->create_at = time();
			}
		];
	}
}