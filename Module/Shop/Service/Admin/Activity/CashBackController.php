<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Service\Admin\Controller;

class CashBackController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_CASH_BACK)->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ActivityModel::findOrNew($id);
        $configure = $model->configure;
        if (empty($configure)) {
            $configure = [
                'order_amount' => 0,
                'money' => 0,
            ];
        }
        if (!isset($configure['star'])) {
            $configure['star'] = 0;
        }
        return $this->show(compact('model', 'configure'));
    }

    public function saveAction() {
        $model = new ActivityModel();
        $model->type = ActivityModel::TYPE_CASH_BACK;
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('activity/cash_back')
        ], '保存成功！');
    }

    public function deleteAction($id) {
        ActivityModel::where('type', ActivityModel::TYPE_CASH_BACK)->where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('activity/cash_back')
        ]);
    }

}