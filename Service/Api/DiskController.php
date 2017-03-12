<?php
namespace Service\Api;


use Zodream\Infrastructure\Disk\Directory;

class DiskController extends Controller {
    public function indexAction() {
        $root = new Directory('H:\\');
        return $this->ajax([]);
    }

}