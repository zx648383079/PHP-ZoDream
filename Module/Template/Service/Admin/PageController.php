<?php
namespace Module\Template\Service\Admin;


use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\ThemeWeightModel;
use Module\Template\Domain\Page;

class PageController extends Controller {

    public function indexAction($id = 0, $site_id = 0, $type = 0) {
        $model = PageModel::findOrDefault($id, ['site_id' => $site_id, 'type' => $type, 'template' => 'index']);
        $style_list = [];
        $site = SiteModel::findWithAuth($model->site_id);
        $weight_list = ThemeWeightModel::groupByType($site->theme_id);
        return $this->show(compact('model', 'style_list', 'weight_list'));
    }

    public function templateAction($id = 0, $edit = false) {
        $this->layout = false;
        $model = PageModel::find($id);
        $page = new Page($model, !empty($edit) && $edit !== 'false');
        app('debugger')->setShowBar(false);
        return $this->show(compact('model', 'page'));
    }

    public function createAction($site_id, $page_id, $type = 0) {
        $model = PageModel::create([
            'site_id' => $site_id,
            'type' => $type,
            'name' => 'new_page',
            'title' => 'New Page',
            'theme_page_id' => $page_id,
            'thumb' => '/assets/images/blog.png'
        ]);
        return $this->jsonSuccess([
            'url' => $this->getUrl('page', ['id' => $model->id])
        ]);
    }

}