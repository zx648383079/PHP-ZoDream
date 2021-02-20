<?php
namespace Module\SMS\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\SMS\Domain\Model\SmsSignatureModel;

class SignatureController extends Controller {

    public function indexAction($keywords = '') {
        $model_list = SmsSignatureModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = SmsSignatureModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('签名不存在');
        }
        return $this->render($model);
    }

    public function saveAction() {
        $model = new SmsSignatureModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        SmsSignatureModel::where('id', $id)->delete();
        return $this->renderData(true);
    }

    public function defaultAction($id) {
        SmsSignatureModel::where('id', $id)->update([
            'is_default' => 1
        ]);
        SmsSignatureModel::where('id', '<>', $id)->update([
            'is_default' => 0
        ]);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            SmsSignatureModel::query()->get('id', 'name')
        );
    }

}