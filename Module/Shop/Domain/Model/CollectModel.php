<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;


/**
 * Class CollectGoodsModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $created_at
 */
class CollectModel extends Model {
    public static function tableName() {
        return 'shop_collect';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'goods_id' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'goods_id' => 'Goods Id',
            'created_at' => 'Created At',
        ];
    }

    public function goods() {
        return $this->hasOne(GoodsModel::class, 'id', 'goods_id');
    }


    public static function add($goods) {
        return static::create([
           'user_id' => auth()->id(),
           'goods_id' => $goods,
        ]);
    }

    public static function exist($goods) {
        return self::where('goods_id', $goods)
            ->where('user_id', auth()->id())->count() > 0;
    }


    public static function remove($goods) {
        return self::where('goods_id', $goods)
            ->where('user_id', auth()->id())->delete();
    }

}