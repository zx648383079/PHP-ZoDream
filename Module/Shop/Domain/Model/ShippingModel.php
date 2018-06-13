<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class PaymentModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 */
class ShippingModel extends Model {
    public static function tableName() {
        return 'shipping';
    }

    /**
     * @return float
     */
    public function getFee() {
        return 0;
    }
}