<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Domain\Model\ThemePageModel;

class SiteController extends Controller {

    public function indexAction($id = 0) {
        $site = SiteModel::findOrDefault($id, ['name' => 'new site', 'title' => 'New Site', 'thumb' => '']);
        if ($site->id > 0 && $site->user_id != auth()->id()) {
            return $this->redirect('./@admin');
        }
        $page_list = [];
        if ($site->id > 0) {
            $page_list = PageModel::where('site_id', $site->id)->orderBy('position asc')->orderBy('id asc')->all();
        }
        $template_list = ThemePageModel::where('theme_id', $site->theme_id)->get();
        return $this->show(compact('site', 'page_list', 'template_list'));
    }


    public function createAction() {
        $theme_list = ThemeModel::query()->get();
        $model = new SiteModel([
                'name' => 'new_site',
                'title' => 'New Site',
                'thumb' => '/assets/images/blog.png',
                'user_id' => auth()->id(),
                'theme_id' => $theme_list[0]->id
        ]);
        return $this->show(compact('model', 'theme_list'));
//        $site = SiteModel::create([
//            'name' => 'new_site',
//            'title' => 'New Site',
//            'thumb' => '/assets/images/blog.png',
//            'user_id' => auth()->id()
//        ]);
//        return $this->jsonSuccess([
//            'url' => $this->getUrl('site', ['id' => $site->id])
//        ]);
    }

    public function editAction($id) {
        $model = SiteModel::findWithAuth($id);
        if (empty($model)) {
            return $this->redirectWithMessage('./', '');
        }
        $theme_list = ThemeModel::query()->get();
        return $this->show('create', compact('model', 'theme_list'));
    }

    public function saveAction() {
        $model = new SiteModel([
            'user_id' => auth()->id()
        ]);
        if ($model->load('', ['user_id']) && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('site', ['id' => $model->id])
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        $model = SiteModel::findWithAuth($id);
        if (empty($model)) {
            return $this->jsonFailure('站点不存在');
        }
        $ids =  PageModel::where('site_id', $id)->pluck('id');
        PageWeightModel::whereIn('page_id', $ids)->delete();
        PageModel::where('site_id', $id)->delete();
        $model->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('')
        ]);
    }

}