<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Zodream\Html\Tree;
use Zodream\Helpers\Tree as TreeHelper;

/**
 * Class ForumLogModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $action
 * @property string $data
 * @property integer $created_at
 */
class ForumLogModel extends Model {

    const TYPE_FORUM = 0;
    const TYPE_THREAD = 1;
    const TYPE_POST = 2;
    const ACTION_EDIT = 1;
    const ACTION_DELETE = 2;
    const ACTION_STATUS = 3;

	public static function tableName() {
        return 'bbs_log';
    }

    protected function rules() {
        return [
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'required|int',
            'data' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'user_id' => 'User Id',
            'action' => 'Action',
            'data' => 'Data',
            'created_at' => 'Created At',
        ];
    }
}