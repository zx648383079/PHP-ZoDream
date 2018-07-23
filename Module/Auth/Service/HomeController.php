<?php
namespace Module\Auth\Service;

use Carbon\Carbon;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;
use Zodream\Infrastructure\Http\URL;

class HomeController extends ModuleController {

    protected function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            return $this->redirect(app('request')->get('redirect_uri', '/'));
        }
        $time = Carbon::today()->startOfDay()->timestamp;
        $num = LoginLogModel::where('ip', app('request')->ip())
            ->where('status', 0)
            ->where('created_at', '>=', $time)->count();
        if ($num > 2) {
            $num = intval($num / 3);
            $this->send('code', $num);
            Factory::session()->set('level', $num);
        }
        $redirect_uri = app('request')->get('redirect_uri');
        $title = '用户登录';
        return $this->show(compact('redirect_uri', 'title'));
    }

    public function checkAction() {
        list($name, $value) = app('request')->get('name,value');
        if (!in_array($name, ['username', 'email'])) {
            return $this->jsonFailure('查询失败！');
        }
        $count = UserModel::where([$name => $value])->count('id')->scalar();
        return $this->jsonSuccess($count > 0);
    }

    public function loginAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            $redirect_uri = app('request')->get('redirect_uri');
            return $this->jsonSuccess([
                'url' => (string)URL::to(empty($redirect_uri) ? '/' : $redirect_uri)
            ], '登录成功！');
        }
        return $this->jsonFailure($user->getFirstError());
    }

    public function logoutAction() {
        Auth::user()->logout();
        return $this->redirect('/');
    }
}