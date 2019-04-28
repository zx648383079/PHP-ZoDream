<?php
namespace Module\CMS\Domain;

use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Helpers\Json;

class ThemeManager {

    /**
     * @var Directory
     */
    protected $src;
    /**
     * @var Directory
     */
    protected $dist;

    public function pack() {

    }

    public function unpack() {

        $file = $this->src->file('theme.json');
        if ($file->exist()) {
            $configs = Json::decode($file->read());
            $this->runScript($configs['script']);
        }
    }

    protected function runScript($data) {

    }
}