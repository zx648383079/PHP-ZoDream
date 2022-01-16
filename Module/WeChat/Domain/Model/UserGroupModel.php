<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $name
 */
class UserGroupModel extends Model {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wechat_user_group';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
        ];
    }

}