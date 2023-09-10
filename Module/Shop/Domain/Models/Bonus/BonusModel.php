<?php
namespace Module\Shop\Domain\Bonus;

use Domain\Model\Model;

/**
 * Class BonusModel
 * @package Domain\Model\Bonus
 * @property integer $id
 * @property integer $user_id
 * @property float $money
 * @property integer $number
 * @property integer $status
 * @property integer $type
 * @property integer $create_at
 */
class BonusModel extends Model {

    const TYPE_NONE = 0;
    const TYPE_RANDOM = 1;

    const STATUS_NONE = 0;
    const STATUS_END = 1;
    const EXPIRE_TIME = 86400;

    public static function tableName(): string {
        return 'shop_bonus';
    }

    public function getMoney() {
        if ($this->status === self::STATUS_END) {
            return false;
        }
        $data = BonusLogModel::query()->where(['bonus_id' => $this->id])->select([
            'num' => 'COUNT(*)',
            'money' => 'SUM(money)'
        ])->asArray()->one();
        if ($this->money === $data['money']) {
            $this->status = self::STATUS_END;
            $this->save();
            return false;
        }
        if ($this->create_at + self::EXPIRE_TIME > time()) {
            // 余款返回用户账户
            $this->status = self::STATUS_END;
            $this->save();
            return false;
        }
        $money = $this->money - $data['money'];
        $num = $this->number - $data['num'];
        if ($this->type === self::TYPE_NONE) {
            return $money / $num;
        }
        if ($num === 1) {
            return $money;
        }
        $min = 0.01;
        $max = $money / $num * 2;
        $arg = rand(0, $max * 100) / 100;
        return $arg <= $min ? 0.01 : $arg;
    }
}