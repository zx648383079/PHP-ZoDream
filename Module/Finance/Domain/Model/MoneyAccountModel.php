<?php
namespace Module\Finance\Domain\Model;


use Domain\Model\Model;

/**
 * 资金账户
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 */
class MoneyAccountModel extends Model {

    public static function tableName() {
        return 'money_account';
    }
}