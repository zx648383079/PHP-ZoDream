<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

class VisitorLogModel extends Model {

    public static function tableName() {
        return 'ctr_visitor_log';
    }
}
