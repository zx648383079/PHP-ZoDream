<?php
namespace Module\WeChat\Service\Admin;


use Module\WeChat\Domain\Model\TemplateModel;

class TemplateController extends Controller {
    public function indexAction($type = 0) {
        if (app('request')->isAjax()) {
            $this->layout = false;
        }
        $model_list = TemplateModel::where('type', intval($type))->page();
        return $this->show(app('request')->isAjax() ? 'page' : 'index', compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = TemplateModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new TemplateModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('template')
            ]);
        }
        return $this->jsonFailure($model->getError());
    }

    public function deleteAction($id) {
        TemplateModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}