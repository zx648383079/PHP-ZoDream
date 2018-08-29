<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\WeightModel;
use Module\Template\Domain\Page;

class MobileController extends Controller {

    public function indexAction() {
        $weight_list = WeightModel::whereIn('adapt_to', [0, 2])->all();
        $page = new Page('index', true);
        return $this->show(compact('weight_list', 'page'));
    }


}