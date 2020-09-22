<?php
namespace Module\OpenPlatform\Service\Api;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class PlatformController extends RestController {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $model_list = PlatformModel::where('user_id', auth()->id())->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = PlatformModel::find($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->renderFailure('应用错误！');
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = OpenRepository::savePlatform($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        PlatformModel::where('id', $id)->where('user_id', auth()->id())->delete();
        return $this->renderData(true);
    }

    public function allAction() {
        $data = PlatformModel::where('user_id', auth()->id())->get('id', 'name');
        return $this->renderData($data);
    }
}