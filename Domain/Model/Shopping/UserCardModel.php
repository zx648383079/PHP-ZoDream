<?php
namespace Domain\Model\Shopping;


use Domain\Model\Model;

/**
 * Class CardModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $card_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $order_id
 * @property integer $update_at
 * @property integer $create_at
 */
class UserCardModel extends Model {

    const NONE = 0;

    const USED = 1; //已使用

    const EXPIRE = 2; //过期

    public static function tableName() {
        return 'user_card';
    }
}