<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

class LogModel extends Model {

    public static function tableName() {
        return 'ctr_log';
    }
}
