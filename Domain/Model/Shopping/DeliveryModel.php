<?php
namespace Domain\Model\Shopping;

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
class DeliveryModel extends Model {
    public static function tableName() {
        return 'delivery';
    }

    /**
     * @return float
     */
    public function getFee() {
        return 0;
    }
}