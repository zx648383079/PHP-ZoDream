<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class PaymentModel
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $method
 * @property string $icon
 * @property string $description
 * @property integer $position
 * @property integer $cod_enabled
 * @property integer $created_at
 * @property integer $updated_at
 * @property array $settings
 */
class ShippingModel extends Model {
    public static function tableName(): string {
        return 'shop_shipping';
    }

    public function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'code' => 'required|string:0,30',
            'method' => 'int:0,99',
            'icon' => 'string:0,100',
            'description' => 'string:0,255',
            'position' => 'int:0,127',
            'cod_enabled' => 'int:0,2',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'code' => 'Code',
            'method' => '计费方式',
            'icon' => '图标',
            'description' => '介绍',
            'cod_enabled' => '货到付款',
            'position' => '排序',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function canUsePayment(PaymentModel $payment) {
        if ($payment->code === PaymentModel::COD_CODE) {
            return $this->cod_enabled > 0;
        }
        return true;
    }
}