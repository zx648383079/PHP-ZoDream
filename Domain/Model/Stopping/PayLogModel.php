<?php
namespace Domain\Model\Shopping;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

class PayLogModel extends Model {
    public static function tableName() {
        return 'pay_log';
    }
}