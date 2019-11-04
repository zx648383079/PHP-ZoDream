<?php
namespace Module\Template;

use Module\Template\Domain\Migrations\CreateTemplateTables;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateTemplateTables();
    }

    /**
     * @param null $path
     * @return bool|Directory|File
     */
    public static function templateFolder($path = null) {
        $folder = new Directory(__DIR__.'/UserInterface/templates');
        if (empty($path)) {
            return $folder;
        }
        return $folder->child($path);
    }
}