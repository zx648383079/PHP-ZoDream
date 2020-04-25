<?php
namespace Module\OpenPlatform\Service;

use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformSimpleModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Zodream\Helpers\Time;

class AuthorizeController extends Controller {

    public function indexAction() {
        $platform_list = PlatformSimpleModel::where('allow_self', 1)->where('status', 1)->get();
        $model_list = UserTokenModel::with('platform')->where('user_id', auth()->id())->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list', 'platform_list'));
    }

    public function saveAction($platform_id) {
        if ($platform_id < 0) {
            return $this->jsonFailure('请选择应用');
        }
        $platform = PlatformModel::where('id', $platform_id)->where('allow_self', 1)->where('status', 1)->first();
        if (!$platform) {
            return $this->jsonFailure('请选择应用');
        }
        $model = UserTokenModel::create([
            'user_id' => auth()->id(),
            'platform_id' => $platform->id,
            'token' => md5(sprintf('%s:%s', auth()->id(), Time::millisecond())),
            'expired_at' => time() + 86400,
            'is_self' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        if (!$model) {
            return $this->jsonFailure('error');
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