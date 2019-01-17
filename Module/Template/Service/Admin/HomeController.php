<?php
namespace Module\Template\Service\Admin;


use Module\Template\Domain\Model\SiteModel;

class HomeController extends Controller {
    public function indexAction() {
        $site_list = SiteModel::where('user_id', auth()->id())->all();
        return $this->show(compact('site_list'));
    }
}