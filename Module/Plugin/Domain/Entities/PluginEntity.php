<?php
declare(strict_types=1);
namespace Module\Plugin\Domain\Entities;


use Domain\Entities\Entity;

class PluginEntity extends Entity {

    public static function tableName() {
        return 'plugin';
    }
}