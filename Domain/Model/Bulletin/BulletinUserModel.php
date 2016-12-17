<?php
namespace Domain\Model\Bulletin;

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
}