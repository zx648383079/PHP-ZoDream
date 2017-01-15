<?php
namespace Domain\Model\CMS;

use Domain\Model\Model;

class BaseModel extends Model {
    public static function site() {
        return 1;
    }

    public static function getExtendTable($table) {
        return sprintf('content_%s_%s', static::site(), $table);
    }
}