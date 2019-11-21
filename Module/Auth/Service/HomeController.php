<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;


use Zodream\Service\Factory;


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
        $time = strtotime(date('Y-m-d 00:00:00'));
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

    public function checkAction($name, $value) {
        if (!in_array($name, ['name', 'email'])) {
            return $this->jsonFailure('查询失败！');
        }
        $count = UserModel::where([$name => $value])->count('id');
        return $this->jsonSuccess($count > 0);
    }

    /**
     * @route login
     * @method GET,POST
     * @return \Zodream\Infrastructure\Http\Response
     * @throws \Exception
     */
    public function loginAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            auth()->user()->logLogin();
            $redirect_uri = app('request')->get('redirect_uri');
            return $this->jsonSuccess([
                'url' => url(empty($redirect_uri) ? '/' : $redirect_uri)
            ], '登录成功！');
        }
        $user->logLogin(false);
        return $this->jsonFailure($user->getFirstError());
    }

    public function logoutAction() {
        auth()->user()->logout();
        return $this->redirect('/');
    }
}