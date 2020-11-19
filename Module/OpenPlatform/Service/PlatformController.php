<?php
namespace Module\OpenPlatform\Service;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;
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
        try {
            OpenRepository::savePlatform($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./platform')
        ]);
    }

    public function deleteAction($id) {
        PlatformModel::where('id', $id)->where('user_id', auth()->id())->delete();
        return $this->renderData([
            'url' => url('./platform')
        ]);
    }
}