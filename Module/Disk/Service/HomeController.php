<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Repositories\ShareRepository;

class HomeController extends Controller {

    public function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction() {
        $model_list = ShareRepository::publicList();
        return $this->show(compact('model_list'));
    }
}