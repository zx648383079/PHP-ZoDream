<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Service\Admin\Controller;

class FreeTrialController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_FREE_TRIAL)->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ActivityModel::findOrNew($id);
        $configure = $model->configure;
        return $this->show(compact('model', 'configure'));
    }

    public function saveAction() {
        $model = new ActivityModel();
        $model->load();
        $model->scope_type = ActivityModel::SCOPE_GOODS;
        $model->type = ActivityModel::TYPE_FREE_TRIAL;
        if (!$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('activity/free_trial')
        ], '保存成功！');
    }

    public function deleteAction($id) {
        ActivityModel::where('type', ActivityModel::TYPE_FREE_TRIAL)->where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/free_trial')
        ]);
    }

}