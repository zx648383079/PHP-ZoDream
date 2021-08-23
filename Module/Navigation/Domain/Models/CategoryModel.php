<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

class CategoryModel extends Model {
    public static function tableName() {
        return 'search_category';
    }
}
