<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

class StayTimeLogModel extends Model {

    public static function tableName() {
        return 'ctr_stay_time_log';
    }
}
