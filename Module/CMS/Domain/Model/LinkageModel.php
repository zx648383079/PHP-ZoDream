<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;

class LinkageModel extends Model {
    public static function tableName() {
        return 'cms_linkage';
    }
}