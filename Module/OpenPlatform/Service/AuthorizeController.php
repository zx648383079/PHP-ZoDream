<?php
namespace Module\OpenPlatform\Service;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformSimpleModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Module\OpenPlatform\Domain\Repositories\OpenRepository;
use Zodream\Helpers\Time;

class AuthorizeController extends Controller {

    public function indexAction() {
        $platform_list = PlatformSimpleModel::where('allow_self', 1)->where('status', 1)->get();
        $model_list = UserTokenModel::with('platform')->where('user_id', auth()->id())->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list', 'platform_list'));
    }

    public function saveAction(int $platform_id) {
        try {
            $model = OpenRepository::createToken($platform_id);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess([
            'url' => url('./authorize')
        ]);
    }

    public function deleteAction($id) {
        UserTokenModel::where('id', $id)->where('user_id', auth()->id())->delete();
        return $this->jsonSuccess([
            'url' => url('./authorize')
        ]);
    }

    public function clearAction() {
        UserTokenModel::where('user_id', auth()->id())->delete();
        return $this->jsonSuccess([
            'url' => url('./authorize')
        ]);
    }
}