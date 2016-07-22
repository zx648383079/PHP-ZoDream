<?php
namespace Domain\Model;
use Zodream\Infrastructure\Database\Record;

/**
* Class BulletinModel
* @property integer $id
* @property string $title
* @property string $content
* @property integer $type
* @property integer $create_at
* @property integer $delete_at
*/
class BulletinModel extends Model {
	public static $table = 'bulletin';

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
	 * @param user|string $user
	 * @param string $title
	 * @param string $content
	 * @param int $type
	 * @return bool|int
	 */
	public static function message($user, $title, $content, $type = 0) {
		$id = (new static)->add([
			'title' => $title,
			'content' => $content,
			'type' => $type,
			'create_at' => time()
		]);
		if (empty($id)) {
			return false;
		}
		$data = [];
		foreach ((array)$user as $item) {
			$data[] = [$id, $item];
		}
		return (new Record())->from('bulletin_user')->batchInsert(['bulletin_id', 'user_id'], $data);
	}
}