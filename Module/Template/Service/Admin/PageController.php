<?php
namespace Module\Template\Service\Admin;


use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\WeightModel;
use Module\Template\Domain\Page;

class PageController extends Controller {

    public function indexAction($id = 0, $site_id = 0, $type = 0) {
        $model = PageModel::findOrDefault($id, ['site_id' => $site_id, 'type' => $type, 'template' => 'index']);
        $page = new Page($model, true);
        $style_list = [];
        $weight_list = WeightModel::groupByType();
        return $this->show(compact('page', 'model', 'style_list', 'weight_list'));
    }

}