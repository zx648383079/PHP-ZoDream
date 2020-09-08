<?php
/*
 * @Author: zodream
 * @Date: 2019-12-29 14:18:54
 * @LastEditors: zodream
 * @LastEditTime: 2020-09-08 22:33:59
 */
namespace Module\Template;

use Module\Template\Domain\Migrations\CreateTemplateTables;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Route\Controller\Module as BaseModule;

/**
 * TODO 可视化开发
 */
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