<?php
namespace Module\Template\Service\Admin;

use Module\ModuleController;
use Module\Template\Domain\Model\WeightModel;

class WebController extends Controller {

    public function indexAction() {
        $weight_list = WeightModel::all();
        return $this->show();
    }


}