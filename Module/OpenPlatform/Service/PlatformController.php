<?php
namespace Module\OpenPlatform\Service;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PlatformController extends Controller {

    public function indexAction() {
        $model_list = PlatformModel::where('user_id', auth()->id())->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = PlatformModel::findOrNew($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->redirectWithMessage('./', '应用错误！');
        }
        return $this->show('edit', compact('model'));
    }

    public function saveAction(Request $request) {
        try {
            OpenRepository::savePlatform($request->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
                'type' => 'int:0,9',
                'domain' => 'required|string:0,50',
                'sign_type' => 'int:0,9',
                'sign_key' => 'string:0,32',
                'encrypt_type' => 'int:0,9',
                'public_key' => '',
                'description' => '',
                'allow_self' => 'int',
            ]));
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