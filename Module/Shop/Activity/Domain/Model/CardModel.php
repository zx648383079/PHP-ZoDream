<?php
namespace Domain\Model\Shopping;


use Domain\Model\Model;

/**
 * Class CardModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $condition 满多少钱
 * @property integer $number 减多少，或打几折
 * @property integer $start_at 开始时间
 * @property integer $end_at 过期时间
 * @property integer $rule  规则
 * @property string $value  分类或商品ID
 * @property integer $overlay  是否允许叠加
 * @property integer $create_at
 */
class CardModel extends Model {

    const TYPE_FULLANDCUT = 0; //满减

    const TYPE_CUT = 1;  //打折

    const RULE_ALL = 0; // 所有分类

    const RULE_IN = 1;  // 特定分类

    const RULE_NOT = 2;  // 除了特定分类

    const RULE_GOODS = 3; //特定商品

    const RULE_NOT_GOODS = 4; //除了特定商品

    public static function tableName() {
        return 'card';
    }

    public function canUse(OrderModel $order) {

    }

    protected function isValidTime($time) {
        return (empty($this->start_at) || $time >= $this->start_at)
            && (empty($this->end_at) || $time <= $this->end_at);
    }

    protected function isValidCondition($money) {
        return empty($this->condition) || $money >= $this->condition;
    }

    protected function isValidGoods(OrderGoodsModel $goods) {

    }
}