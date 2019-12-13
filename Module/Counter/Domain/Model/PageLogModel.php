<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

class PageLogModel extends Model {

    public static function tableName() {
        return 'ctr_page_log';
    }
}
