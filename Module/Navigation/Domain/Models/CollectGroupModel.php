<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

class CollectGroupModel extends Model {
    public static function tableName() {
        return 'search_collect_group';
    }
}
