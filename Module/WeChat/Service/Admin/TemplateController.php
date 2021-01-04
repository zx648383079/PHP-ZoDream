<?php
namespace Module\WeChat\Service\Admin;


use Module\WeChat\Domain\Model\MediaTemplateModel;

class TemplateController extends Controller {
    public function indexAction($type = 0) {
        if (request()->isAjax()) {
            $this->layout = false;
        }
        $model_list = MediaTemplateModel::where('type', intval($type))->page();
        return $this->show(request()->isAjax() ? 'page' : 'index', compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = MediaTemplateModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new MediaTemplateModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('template')
            ]);
        }
        return $this->renderFailure($model->getError());
    }

    public function deleteAction($id) {
        MediaTemplateModel::where('id', $id)->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }
}