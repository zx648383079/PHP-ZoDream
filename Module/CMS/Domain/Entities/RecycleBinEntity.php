<?php
namespace Module\CMS\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 */
class RecycleBinEntity extends Entity {
    public static function tableName(): string {
        return 'cms_recycle_bin';
    }

}