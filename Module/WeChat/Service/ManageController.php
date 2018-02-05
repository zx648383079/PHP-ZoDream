<?php
namespace Module\WeChat\Service;

use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Service\Routing\Url;

class ManageController extends Controller {

    protected function rules() {
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
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = WeChatModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new WeChatModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => (string)Url::to('./manage')
            ]);
        }

        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        WeChatModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function changeAction($id) {
        $model = WeChatModel::find($id);
        if (empty($model)) {
            return $this->redirect('./manage');
        }
        $this->weChatId($id);
        return $this->redirect('./manage');
    }
}