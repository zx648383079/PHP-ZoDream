<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

/**
 * Class BankModel
 * @package Module\Finance\Domain\Model
 */
class BankModel extends Model {
    public static function tableName() {
        return 'bank';
    }


}