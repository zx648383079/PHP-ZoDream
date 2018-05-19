<?php
namespace Module\Disk\Service;

use Module\ModuleController;
use Zodream\Service\Factory;

class Controller extends ModuleController {

    protected $configs;

    protected function rules() {
        return [
          '*' => '@'
        ];
    }

    public function init() {
        $this->configs = Factory::config('disk', [
            'cache' => 'data/disk/cache/',
            'disk' => 'data/disk/file/'
        ]);
    }

    public static function noFound() {
        throw new \ErrorException('404');
    }
}