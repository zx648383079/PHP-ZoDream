<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class BlogLogModel
 * @property integer $id
 * @property integer $type
 * @property integer $id_value
 * @property integer $user_id
 * @property integer $action
 * @property integer $created_at
 */
class BlogLogModel extends Model {

    const TYPE_BLOG = 0;
    const TYPE_COMMENT = 1;

    const ACTION_RECOMMEND = 0;
    const ACTION_AGREE = 1;
    const ACTION_DISAGREE = 2;

    const ACTION_REAL_RULE = 3; // 是否能阅读


	public static function tableName() {
        return 'blog_log';
    }


    protected function rules() {
        return [
            'type' => 'int:0-3',
            'id_value' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'id_value' => 'Id Value',
            'user_id' => 'User Id',
            'action' => 'Action',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function blog() {
        return $this->hasOne(BlogSimpleModel::class, 'id', 'blog_id');
    }

}