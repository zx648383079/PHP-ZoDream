<?php
namespace Module\Template\Domain\Model\Base;


use Domain\Model\Model;

/**
 * Class FriendLinkModel
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $logo
 * @property string $brief
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class FriendLinkModel extends Model {
    public static function tableName() {
        return 'base_friend_link';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'url' => 'required|string:0,50',
            'logo' => 'string:0,200',
            'brief' => 'string:0,255',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'url' => 'Url',
            'logo' => 'Logo',
            'brief' => 'Brief',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}