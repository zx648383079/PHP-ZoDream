<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;
use Zodream\Helpers\Str;

/**
 * Class ShopGoodsCardModel
 * @property integer $id
 * @property integer $goods_id
 * @property string $card_no
 * @property string $card_pwd
 * @property integer $order_id
 */
class GoodsCardModel extends Model {
    public static function tableName() {
        return 'shop_goods_card';
    }

    protected function rules() {
        return [
            'goods_id' => 'required|int',
            'card_no' => 'required|string:0,255',
            'card_pwd' => 'required|string:0,255',
            'order_id' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'card_no' => '卡号',
            'card_pwd' => '密码',
            'order_id' => 'Order Id',
        ];
    }


    public static function generate($goods, $amount) {
        $data = [];
        $prefix = date('YmdHis');
        for (; $amount >= 0; $amount --) {
            $data[] = [
                'goods_id' => $goods,
                'card_no' => $prefix.str_pad($amount, 6, 0, STR_PAD_LEFT),
                'card_pwd' => Str::random(20),
            ];
        }
        if (empty($data)) {
            return;
        }
        static::query()->insert($data);
    }

    public static function refreshStock($goods) {
        $count = static::where('goods_id', $goods)->where('order_id', '<', 1)
            ->count();
        GoodsModel::where('id', $goods)->update([
            'stock' => $count
        ]);
    }

}