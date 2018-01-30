<?php
namespace Module\WeChat\Service;

use Module\ModuleController;
use Module\WeChat\Domain\Model\FansModel;

class UserController extends ModuleController {

    public function indexAction() {
        $model_list = FansModel::with('user')->page();
        return $this->show(compact('model_list'));
    }
}