<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

class NavigationModel extends Model {
    public static function tableName() {
        return 'navigation';
    }
}