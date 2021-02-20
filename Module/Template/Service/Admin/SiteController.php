<?php
namespace Module\Template\Service\Admin;

use Domain\Model\SearchModel;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Domain\Model\ThemePageModel;

class SiteController extends Controller {

    public function indexAction() {
        $site_list = SiteModel::where('user_id', auth()->id())->all();
        return $this->show(compact('site_list'));
    }

    public function pageAction($id = 0) {
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

    public function createAction($theme_id = 0, $keywords = null) {
        if ($theme_id < 1) {
            $model_list = ThemeModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->orderBy('id', 'desc')
                ->page();
            return $this->show('theme', compact('model_list', 'keywords'));
        }
        $theme = ThemeModel::find($theme_id);
        $model = new SiteModel([
                'name' => 'new_site',
                'title' => 'New Site',
                'thumb' => '/assets/images/thumb.jpg',
                'user_id' => auth()->id(),
                'theme_id' => $theme->id
        ]);
        return $this->show(compact('model', 'theme'));
    }

    public function editAction($id) {
        $model = SiteModel::findWithAuth($id);
        if (empty($model)) {
            return $this->redirectWithMessage('./', '');
        }
        $theme = ThemeModel::find($model->theme_id);
        return $this->show('create', compact('model', 'theme'));
    }

    public function saveAction() {
        $model = new SiteModel([
            'user_id' => auth()->id()
        ]);
        if (!$model->load('', ['user_id']) || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('site/page', ['id' => $model->id])
        ]);
    }

    public function deleteAction($id) {
        $model = SiteModel::findWithAuth($id);
        if (empty($model)) {
            return $this->renderFailure('站点不存在');
        }
        $ids =  PageModel::where('site_id', $id)->pluck('id');
        PageWeightModel::whereIn('page_id', $ids)->delete();
        PageModel::where('site_id', $id)->delete();
        $model->delete();
        return $this->renderData([
            'url' => $this->getUrl('site')
        ]);
    }

}