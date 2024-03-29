<?php
namespace Module\Contact\Domain\Model;


use Domain\Model\Model;

/**
 * Class FriendLinkModel
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $logo
 * @property string $brief
 * @property string $email
 * @property integer $status
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class FriendLinkModel extends Model {
    public static function tableName(): string {
        return 'cif_friend_link';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,20',
            'url' => 'required|string:0,50',
            'logo' => 'string:0,200',
            'brief' => 'string:0,255',
            'email' => 'string:0,100',
            'status' => 'int:0,9',
            'user_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'url' => 'Url',
            'logo' => 'Logo',
            'brief' => 'Brief',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function setUrlAttribute($value) {
        if (empty($value)) {
            return;
        }
        $this->__attributes['url'] = !str_contains($value, '://') ? 'http://'. $value : $value;
    }

}