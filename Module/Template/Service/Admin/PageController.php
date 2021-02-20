<?php
namespace Module\Template\Service\Admin;


use Domain\Model\SearchModel;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\ThemeModel;
use Module\Template\Domain\Model\ThemePageModel;
use Module\Template\Domain\Model\ThemeStyleModel;
use Module\Template\Domain\Model\ThemeWeightModel;
use Module\Template\Domain\Page;

class PageController extends Controller {

    public function indexAction($id = 0, $site_id = 0, $type = 0) {
        $model = PageModel::findOrDefault($id, ['site_id' => $site_id, 'type' => $type, 'template' => 'index']);
        $site = SiteModel::findWithAuth($model->site_id);
        $theme = ThemeModel::find($site->theme_id);
        $weight_list = ThemeWeightModel::groupByType($site->theme_id);
        $style_list = ThemeStyleModel::where('theme_id', $site->theme_id)->get();
        return $this->show(compact('model', 'style_list', 'weight_list', 'theme'));
    }

    public function templateAction($id = 0, $edit = false) {
        $this->layout = false;
        $model = PageModel::find($id);
        $page = new Page($model, !empty($edit) && $edit !== 'false');
        app('debugger')->setShowBar(false);
        return $this->show(compact('model', 'page'));
    }

    public function createAction($site_id, $page_id = 0, $type = 0, $keywords = null) {
        $site = SiteModel::find($site_id);
        if ($page_id < 1) {
            $model_list = ThemePageModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->where('theme_id', $site->theme_id)->orderBy('id', 'desc')
                ->page();
            return $this->show('theme', compact('model_list', 'keywords', 'site_id', 'type'));
        }
        $theme = ThemePageModel::find($page_id);
        $model = new PageModel([
            'site_id' => $site_id,
            'type' => $type,
            'name' => 'new_page',
            'title' => 'New Page',
            'theme_page_id' => $page_id,
            'thumb' => '/assets/images/thumb.jpg',
            'position' => 99
        ]);
        return $this->show(compact('model', 'theme'));
    }

    public function editAction($id) {
        $model = PageModel::find($id);
        if (empty($model)) {
            return $this->redirectWithMessage($this->getUrl('site'), '');
        }
        $theme = ThemePageModel::find($model->theme_page_id);
        return $this->show('create', compact('model', 'theme'));
    }

    public function saveAction() {
        $model = new PageModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('site/page', ['id' => $model->site_id])
        ]);
    }

    public function deleteAction($id) {
        $model = PageModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('页面不存在');
        }
        PageWeightModel::where('page_id', $id)->delete();
        $model->delete();
        return $this->renderData([
            'url' => $this->getUrl('site/page', ['id' => $model->site_id])
        ]);
    }

}