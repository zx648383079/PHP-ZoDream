<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\SiteModel;

class SiteController extends Controller {

    public function indexAction($id = 0) {
        $site = SiteModel::findOrDefault($id, ['name' => 'new site', 'title' => 'New Site', 'thumb' => '']);
        if ($site->id > 0 && $site->user_id != auth()->id()) {
            return $this->redirect('./admin');
        }
        $page_list = [];
        if ($site->id > 0) {
            $page_list = PageModel::where('site_id', $site->id)->orderBy('position asc')->orderBy('id asc')->all();
        }
        return $this->show(compact('site', 'page_list'));
    }


    public function createAction() {
        $site = SiteModel::create([
            'name' => 'new_site',
            'title' => 'New Site',
            'thumb' => '/assets/images/blog.png',
            'user_id' => auth()->id()
        ]);
        return $this->jsonSuccess([
            'url' => $this->getUrl('site', ['id' => $site->id])
        ]);
    }

    public function saveAction() {
        $model = new SiteModel([
            'user_id' => auth()->id()
        ]);
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('site', ['id' => $model->id])
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }


}