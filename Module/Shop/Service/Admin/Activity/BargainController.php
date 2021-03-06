<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Service\Admin\Controller;

class BargainController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_BARGAIN)->orderBy('id', 'desc')->page();
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
        $model->scope_type = ActivityModel::SCOPE_GOODS;
        $model->type = ActivityModel::TYPE_BARGAIN;
        if (!$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('activity/bargain')
        ], '保存成功！');
    }

    public function deleteAction($id) {
        ActivityModel::where('type', ActivityModel::TYPE_BARGAIN)->where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('activity/bargain')
        ]);
    }

}