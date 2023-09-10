<?php
namespace Module\Shop\Domain\Models;


use Domain\Model\Model;

/**
 * Class WarehouseGoodsModel
 * @package Module\Shop\Domain\Models
 * @property integer $warehouse_id
 * @property integer $goods_id
 * @property integer $product_id
 * @property integer $amount
 */
class WarehouseGoodsModel extends Model {

    public static function tableName(): string {
        return 'shop_warehouse_goods';
    }

    protected function rules(): array {
        return [
            'warehouse_id' => 'required|int',
            'goods_id' => 'required|int',
            'product_id' => 'int',
            'amount' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'ID',
            'warehouse_id' => 'Warehouse Id',
            'goods_id' => 'Goods Id',
            'product_id' => 'Product Id',
            'amount' => 'Amount',
        ];
    }

    public function warehouse() {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id');
    }

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'goods_id');
    }

    public function product() {
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }
}