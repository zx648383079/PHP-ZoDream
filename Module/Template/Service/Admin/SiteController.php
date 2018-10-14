<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\SiteModel;

class SiteController extends Controller {

    public function indexAction($id = 0) {
        $site = SiteModel::findOrDefault($id, ['name' => 'new site', 'title' => 'New Site', 'thumb' => '']);
        $page_list = [];
        if ($site->id > 0) {
            $page_list = PageModel::where('site_id', $site->id)->orderBy('position asc')->orderBy('id asc')->all();
        }
        return $this->show(compact('site', 'page_list'));
    }


}