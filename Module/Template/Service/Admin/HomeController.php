<?php
namespace Module\Template\Service\Admin;


use Module\Template\Domain\Model\SiteModel;

class HomeController extends Controller {
    public function indexAction() {
        $site_list = SiteModel::all();
        return $this->show(compact('site_list'));
    }
}