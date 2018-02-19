<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;

/**
 * Class CollectGoodsModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $is_attention
 * @property integer $create_at
 */
class CollectGoodsModel extends Model {
    public static function tableName() {
        return 'collect_goods';
    }
}