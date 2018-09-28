<?php
namespace Module\Auth\Domain\Model\Bulletin;

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
        return [
            'title' => 'required|string:0,100',
            'content' => 'required|string:0,255',
            'type' => 'int:0,99',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'content' => 'Content',
            'type' => 'Type',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
		return BulletinUserModel::query()->insert(['bulletin_id', 'user_id'], $data);
	}
}