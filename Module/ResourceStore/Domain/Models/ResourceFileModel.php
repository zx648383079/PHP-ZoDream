<?php
declare(strict_types=1);
namespace Module\ResourceStore\Domain\Models;

use Domain\Model\Model;

class ResourceFileModel extends Model {
    public static function tableName() {
        return 'res_resource_file';
    }

}