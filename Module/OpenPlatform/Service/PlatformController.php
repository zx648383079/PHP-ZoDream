<?php
namespace Module\OpenPlatform\Service;


use Module\OpenPlatform\Domain\Model\PlatformModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Routing\Url;

class PlatformController extends Controller {

    public function indexAction() {
        $model_list = PlatformModel::where('user_id', Auth::id())->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = PlatformModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $id = intval(Request::request('id'));
        $data = Request::post();
        unset($data['appid']);
        unset($data['secret']);
        if ($id > 0) {
            $model = PlatformModel::where('user_id', Auth::id())
                ->where('id', $id)->one();
        } else {
            $model = new PlatformModel();
            $model->generateNewId();
        }
        if (empty($model)) {
            return $this->jsonFailure('应用不存在');
        }
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => (string)Url::to('./platform')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        PlatformModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => (string)Url::to('./platform')
        ]);
    }
}