<?php
namespace Module;

use Zodream\Service\Controller\ModuleController as BaseController;

abstract class ModuleController extends BaseController {

    public function getConfig() {
        return [];
    }

    public function setConfig($data) {

    }
}