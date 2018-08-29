<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;

class SiteModel extends Model {
    public static function tableName() {
        return 'tpl_site';
    }
}