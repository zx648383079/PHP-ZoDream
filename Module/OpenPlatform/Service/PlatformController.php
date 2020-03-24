<?php
namespace Module\OpenPlatform\Service;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Zodream\Infrastructure\Http\Request;

class PlatformController extends Controller {

    public function indexAction() {
        $model_list = PlatformModel::where('user_id', auth()->id())->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = PlatformModel::findOrNew($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->redirectWithMessage('./', '应用错误！');
        }
        return $this->show(compact('model'));
    }

    public function saveAction(Request $request) {
        $id = intval($request->get('id'));
        $data = $request->get();
        unset($data['appid']);
        unset($data['secret']);
        unset($data['rules']);
        unset($data['status']);
        if ($id > 0) {
            $model = PlatformModel::where('user_id', auth()->id())
                ->where('id', $id)->one();
        } else {
            $model = new PlatformModel();
            $model->user_id = auth()->id();
            $model->generateNewId();
            $model->status = PlatformModel::STATUS_WAITING;
        }
        if (empty($model)) {
            return $this->jsonFailure('应用不存在');
        }
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => url('./platform')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        PlatformModel::where('id', $id)->where('user_id', auth()->id())->delete();
        return $this->jsonSuccess([
            'url' => url('./platform')
        ]);
    }
}