<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Service\Admin\Controller;

class AuctionController extends Controller {

    public function indexAction() {
        $model_list = ActivityModel::where('type', ActivityModel::TYPE_AUCTION)->orderBy('id', 'desc')->page();
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
        $model->type = ActivityModel::TYPE_AUCTION;
        if (!$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('activity/auction')
        ], '保存成功！');
    }

    public function deleteAction($id) {
        ActivityModel::where('type', ActivityModel::TYPE_AUCTION)->where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('activity/auction')
        ]);
    }

}