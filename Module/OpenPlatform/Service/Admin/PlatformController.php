<?php
namespace Module\OpenPlatform\Service\Admin;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Zodream\Infrastructure\Http\Request;

class PlatformController extends Controller {

    public function indexAction() {
        $model_list = PlatformModel::orderBy('status', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function editAction($id) {
        $model = PlatformModel::find($id);
        return $this->show(compact('model'));
    }

    public function saveAction(Request $request) {
        $id = intval($request->get('id'));
        $data = $request->get();
        unset($data['appid']);
        unset($data['secret']);
        $model = PlatformModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('应用不存在');
        }
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('platform')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        PlatformModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('platform')
        ]);
    }
}