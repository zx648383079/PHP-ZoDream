<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

class ClickLogModel extends Model {

    public static function tableName() {
        return 'ctr_click_log';
    }
}
