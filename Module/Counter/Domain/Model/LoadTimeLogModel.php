<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

class LoadTimeLogModel extends Model {

    public static function tableName() {
        return 'ctr_load_time_log';
    }
}
