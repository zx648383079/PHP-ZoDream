<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Service\Admin\Controller;

class LotteryController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_LOTTERY)->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = ActivityModel::findOrNew($id);
        $configure = $model->configure;
        return $this->show('edit', compact('model', 'configure'));
    }

    public function saveAction() {
        $model = new ActivityModel();
        $model->load();
        $model->type = ActivityModel::TYPE_LOTTERY;
        if (!$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('activity/lottery')
        ], '保存成功！');
    }

    public function deleteAction($id) {
        ActivityModel::where('type', ActivityModel::TYPE_LOTTERY)->where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/lottery')
        ]);
    }

}