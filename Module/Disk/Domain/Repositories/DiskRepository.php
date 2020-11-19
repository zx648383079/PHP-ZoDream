<?php
namespace Module\Disk\Domain\Repositories;

use Module\Disk\Domain\Adapters\BaseDiskAdapter;
use Module\Disk\Domain\Adapters\Database;
use Module\Disk\Domain\Adapters\IDiskAdapter;

class DiskRepository {

    private static $driver;

    /**
     * @return IDiskAdapter|BaseDiskAdapter
     */
    public static function driver() {
        if (!empty(static::$driver)) {
            return static::$driver;
        }
        $configs = config('disk', [
            'driver' => Database::class,
            'cache' => 'data/disk/cache/',
            'disk' => 'data/disk/file/'
        ]);
        $driver = $configs['driver'];
        return static::$driver = new $driver($configs);
    }
}