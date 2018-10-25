<?php
namespace Module\WeChat\Service\Admin;


use Module\WeChat\Domain\Model\TemplateModel;

class TemplateController extends Controller {
    public function indexAction($type) {
        $this->layout = false;
        $model_list = TemplateModel::where('type', intval($type))->page();
        return $this->show(compact('model_list'));
    }
}