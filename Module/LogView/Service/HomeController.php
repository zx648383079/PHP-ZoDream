<?php
declare(strict_types=1);
namespace Module\LogView\Service;

use Module\LogView\Domain\Model\FileModel;

class HomeController extends Controller {


    public function indexAction() {
        $file_list = FileModel::all();
        return $this->show(compact('file_list'));
    }
}