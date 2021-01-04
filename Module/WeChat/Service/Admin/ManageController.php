<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\WeChatModel;


class ManageController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $model_list = WeChatModel::all();
        $current_id = $this->weChatId();
        return $this->show(compact('model_list', 'current_id'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = WeChatModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new WeChatModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('manage')
            ]);
        }

        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        WeChatModel::where('id', $id)->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function changeAction($id) {
        $model = WeChatModel::find($id);
        if (empty($model)) {
            return $this->redirect($this->getUrl('manage'));
        }
        $this->weChatId($id);
        return $this->redirect($this->getUrl('manage'));
    }
}