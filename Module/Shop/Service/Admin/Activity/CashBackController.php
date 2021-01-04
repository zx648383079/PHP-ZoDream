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
        return $this->editAction(0);
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
        return $this->show('edit', compact('model', 'configure'));
    }

    public function saveAction() {
        $model = new ActivityModel();
        $model->type = ActivityModel::TYPE_CASH_BACK;
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('activity/cash_back')
        ], '保存成功！');
    }

    public function deleteAction($id) {
        ActivityModel::where('type', ActivityModel::TYPE_CASH_BACK)->where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/cash_back')
        ]);
    }

}