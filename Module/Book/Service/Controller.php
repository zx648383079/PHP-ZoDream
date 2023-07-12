<?php
declare(strict_types=1);
namespace Module\Book\Service;

use Module\Book\Domain\Repositories\CategoryRepository;
use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    public File|string $layout = 'main';

    public function prepare() {
        $cat_list = CategoryRepository::getList();
        $site_name = 'ZoDream 读书';
        $hot_keywords = [];
        $this->send(compact('cat_list', 'site_name', 'hot_keywords'));
    }
}