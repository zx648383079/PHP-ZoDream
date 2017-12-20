<?php
namespace Module\Template\Service;

use Module\ModuleController;
use Module\Template\Domain\Model\WeightModel;

class HomeController extends ModuleController {

    public function indexAction() {
        $weight_list = WeightModel::all();
        return $this->show();
    }


}