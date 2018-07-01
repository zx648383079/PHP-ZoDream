<?php
namespace Module\OpenPlatform\Domain\Model;


use Domain\Model\Model;

class UserTokenModel extends Model {
    public static function tableName() {
        return 'open_user_token';
    }
}