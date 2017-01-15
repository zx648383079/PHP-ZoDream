<?php
namespace Domain\Model\Bonus;

use Domain\Model\Model;

/**
 * Class BonusModel
 * @package Domain\Model\Bonus
 * @property integer $id
 * @property integer $bonus_id
 * @property integer $user_id
 * @property float $money
 * @property integer $create_at
 */
class BonusLogModel extends Model {
    public static function tableName() {
        return 'bonus_log';
    }
}