<?php
namespace Module\Shop\Domain\Models\Activity;

use Domain\Model\Model;

class PresaleLogModel extends Model {

    public static function tableName() {
        return 'shop_presale_log';
    }

}