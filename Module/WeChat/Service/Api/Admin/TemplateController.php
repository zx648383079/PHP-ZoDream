<?php
namespace Module\WeChat\Service\Api\Admin;


use Module\WeChat\Domain\Model\MediaTemplateModel;

class TemplateController extends Controller {
    public function indexAction($type = 0) {
        $model_list = MediaTemplateModel::where('type', intval($type))->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = MediaTemplateModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new MediaTemplateModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        MediaTemplateModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}