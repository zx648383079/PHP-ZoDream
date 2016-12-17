<?php
namespace Domain\Model\Bulletin;

use Domain\Model\Model;
use Zodream\Service\Factory;

/**
 * Class BulletinModel
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $user_id
 * @property integer $create_at
 * @property integer $delete_at
 */
class BulletinModel extends Model {
	public static function tableName() {
        return 'bulletin';
    }

    protected function rules() {
		return array (
		  'title' => 'required|string:3-100',
		  'content' => '',
		  'type' => '|int',
		  'create_at' => '|int',
		  'delete_at' => '|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'title' => 'Title',
		  'content' => 'Content',
		  'type' => 'Type',
		  'create_at' => 'Create At',
		  'delete_at' => 'Delete At',
		);
	}

	/**
	 * SEND MESSAGE TO ANY USERS
	 * 
	 * @param array|string $user
	 * @param string $title
	 * @param string $content
	 * @param int $type
	 * @return bool|int
	 */
	public static function message($user, $title, $content, $type = 0) {
	    $bulletin = new static();
	    $bulletin->title = $title;
	    $bulletin->content = $content;
	    $bulletin->type = $type;
	    $bulletin->create_at = time();
	    $bulletin->user_id = Factory::user()->getId();
		if (!$bulletin->save()) {
			return false;
		}
		$data = [];
		foreach ((array)$user as $item) {
			$data[] = [$bulletin->id, $item];
		}
		return BulletinUserModel::batchInsert(['bulletin_id', 'user_id'], $data);
	}
}