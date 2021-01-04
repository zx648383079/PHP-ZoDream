<?php
namespace Module\OpenPlatform\Service\Api\Admin;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PlatformController extends Controller {

    public function indexAction() {
        $model_list = PlatformModel::orderBy('status', 'desc')->page();
        return $this->renderPage($model_list);
    }

    public function editAction($id) {
        $model = PlatformModel::find($id);
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        $id = intval($request->get('id'));
        $data = $request->get();
        unset($data['appid']);
        unset($data['secret']);
        $model = PlatformModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('应用不存在');
        }
        if ($model->load() && $model->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        PlatformModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}