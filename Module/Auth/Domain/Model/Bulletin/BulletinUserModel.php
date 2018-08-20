<?php
namespace Module\Auth\Domain\Model\Bulletin;

use Domain\Model\Model;
/**
 * Class BulletinModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $bulletin_id
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class BulletinUserModel extends Model {

    const NONE = 0;
    const READ = 1;  // 已阅读

	public static function tableName() {
        return 'bulletin_user';
    }

    protected function rules() {
        return [
            'bulletin_id' => 'required|int',
            'status' => 'int:0,9',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'bulletin_id' => 'Bulletin Id',
            'status' => 'Status',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}