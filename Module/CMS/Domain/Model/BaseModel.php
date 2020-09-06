<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;
use Module\CMS\Domain\Repositories\CMSRepository;

class BaseModel extends Model {

    public static function getExtendTable($table) {
        return sprintf('content_%s_%s', CMSRepository::siteId(), $table);
    }
}