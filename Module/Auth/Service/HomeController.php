<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\CaptchaRepository;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Service\Http\Request;


class HomeController extends Controller {

    public function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction(Request $request) {
        $redirect_uri = $request->string('redirect_uri', '/');
        if ($request->isGet() && $request->has('ticket')) {
            if (AuthRepository::verifyTicket($request->get('ticket'), true) > 0) {
                return $this->redirect($redirect_uri);
            }
        }
        try {
            AuthRepository::login(
                $request->string('email'),
                $request->string('password'),
                $request->has('rememberMe'));
            return $this->redirect($redirect_uri);
        } catch (\Exception $ex) {}
        if ($request->isAjax() && $request->isGet() && !$request->isPjax()) {
            return $this->render([
                'code' => 302,
                'status' => __('failure'),
                'errors' => '重定向',
                'url' => url(['redirect_uri' => $request->referrer()])
            ]);
        }
        $time = strtotime(date('Y-m-d 00:00:00'));
        $count = LoginLogModel::failureCount($request->ip(), $time);
        $isCaptcha = false;
        if ($count > 2) {
            $count = intval($count / 3);
            session()->set('level', $count);
            $isCaptcha = true;
        }
        $title = '用户登录';
        return $this->show(compact('redirect_uri', 'title', 'isCaptcha'));
    }

    public function checkAction(string $name, string $value) {
        if (!in_array($name, ['name', 'email'])) {
            return $this->renderFailure('查询失败！');
        }
        $count = UserModel::where($name, $value)->count('id');
        return $this->renderData($count > 0);
    }

    /**
     * @route login
     * @method GET, POST
     * @param $email
     * @param Request $request
     * @return Output
     * @throws \Exception
     */
    public function loginAction(Request $request, string $email = '') {
        try {
            $captcha = $request->string('captcha');
            AuthRepository::loginPreCheck($request->ip(), $email, $captcha);
            if (!empty($captcha) && !CaptchaRepository::verify($captcha)) {
                throw AuthException::invalidCaptcha();
            }
            AuthRepository::login(
                $email,
                $request->string('password'),
                $request->has('rememberMe'));
        } catch (\Exception $ex) {
            // 是否需要显示验证码
            if (!empty($email) && $ex->getCode() < 1009) {
                LoginLogModel::addLoginLog($email, 0, false);
            }
            return $this->renderFailure($ex->getMessage(), $ex->getCode());
        }
        $redirect_uri = $request->get('redirect_uri');
        return $this->renderData([
            'url' => url(empty($redirect_uri) ? '/' : $redirect_uri)
        ], '登录成功！');
    }

    public function logoutAction() {
        auth()->user()->logout();
        return $this->redirect('/');
    }
}