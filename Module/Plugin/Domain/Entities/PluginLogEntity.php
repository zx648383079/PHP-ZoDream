<?php
declare(strict_types=1);
namespace Module\Plugin\Domain\Entities;


use Domain\Entities\Entity;

class PluginLogEntity extends Entity {

    public static function tableName() {
        return 'plugin_log';
    }
}