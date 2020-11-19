<?php
namespace Module\Disk\Domain\Adapters;

use Zodream\Service\Factory;

abstract class BaseDiskAdapter {

    protected $configs = [];

    public function __construct($configs = []) {
        if (empty($configs)) {
            return;
        }
        $this->configs = array_merge($this->configs, $configs);
    }



    public function cacheFolder() {
        return $this->getRealFolder($this->configs['cache']);
    }

    /**
     * 根节点
     * @return \Zodream\Disk\Directory
     * @throws \Exception
     */
    public function root() {
        return $this->getRealFolder($this->configs['disk']);
    }

    /**
     * @param $path
     * @return \Zodream\Disk\Directory
     * @throws \Exception
     */
    protected function getRealFolder($path) {
        if (empty($path)) {
            throw new \Exception('文件夹错误');
        }
        return Factory::root()->directory($path);
    }
}