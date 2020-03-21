<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Service\Admin\Controller;

class PresaleController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_PRE_SALE)->orderBy('id', 'desc')->page();
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

    public function saveAction($configure) {
        $model = new ActivityModel();
        $model->load();
        $model->type = ActivityModel::TYPE_PRE_SALE;
        $model->scope_type = ActivityModel::SCOPE_GOODS;
        $configure['step'] = self::formArr($configure['step']);
        if (!$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('activity/presale')
        ], '保存成功！');
    }

    public function deleteAction($id) {
        ActivityModel::where('type', ActivityModel::TYPE_PRE_SALE)->where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('activity/presale')
        ]);
    }

}