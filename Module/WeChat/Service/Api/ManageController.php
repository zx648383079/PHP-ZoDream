<?php
namespace Module\WeChat\Service\Api;

use Module\WeChat\Domain\Model\WeChatModel;


class ManageController extends Controller {

    public function indexAction() {
        $model_list = WeChatModel::all();
        return $this->renderData($model_list);
    }

    public function detailAction($id) {
        $model = WeChatModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new WeChatModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }

        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        WeChatModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}