<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;
use Zodream\Domain\Access\Auth;

/**
 * Class CollectGoodsModel
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


    public static function add($goods) {
        return static::create([
           'user_id' => Auth::id(),
           'goods_id' => $goods,
        ]);
    }

    public static function exist($goods) {
        return self::where('goods_id', $goods)
            ->where('user_id', Auth::id())->count() > 0;
    }


    public static function remove($goods) {
        return self::where('goods_id', $goods)
            ->where('user_id', Auth::id())->delete();
    }

}