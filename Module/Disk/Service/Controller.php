<?php
namespace Module\Disk\Service;

use Module\ModuleController;
use Zodream\Disk\Directory;
use Zodream\Service\Factory;

class Controller extends ModuleController {

    public $layout = 'main';

    /**
     * @var Directory
     */
    protected $cacheFolder;
    /**
     * @var Directory
     */
    protected $diskFolder;

    protected function rules() {
        return [
          '*' => '@'
        ];
    }

    public function init() {
        $configs = Factory::config('disk', [
            'cache' => 'data/disk/cache/',
            'disk' => 'data/disk/file/'
        ]);
        $this->cacheFolder = Factory::root()->directory($configs['cache']);
        $this->diskFolder = Factory::root()->directory($configs['disk']);
        $this->cacheFolder->create();
        $this->diskFolder->create();
    }

    public static function noFound() {
        throw new \ErrorException('404');
    }
}