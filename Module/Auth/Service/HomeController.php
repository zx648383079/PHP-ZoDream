<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Image\Captcha;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Http\Response;
use Zodream\Service\Factory;


class HomeController extends Controller {

    protected function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction(Request $request) {
        try {
            AuthRepository::login(
                $request->get('email'),
                $request->get('password'),
                $request->has('rememberMe'));
            return $this->redirect($request->get('redirect_uri', '/'));
        } catch (\Exception $ex) {}
        if ($request->isAjax() && $request->isGet()) {
            return $this->renderResponse([
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
            Factory::session()->set('level', $count);
            $isCaptcha = true;
        }
        $redirect_uri = $request->get('redirect_uri');
        $title = '用户登录';
        return $this->show(compact('redirect_uri', 'title', 'isCaptcha'));
    }

    public function checkAction($name, $value) {
        if (!in_array($name, ['name', 'email'])) {
            return $this->renderFailure('查询失败！');
        }
        $count = UserModel::where([$name => $value])->count('id');
        return $this->renderData($count > 0);
    }

    /**
     * @route login
     * @method GET, POST
     * @param $email
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function loginAction(Request $request, $email = null) {
        try {
            $captcha = $request->get('captcha');
            AuthRepository::loginPreCheck($request->ip(), $email, $captcha);
            if (!empty($captcha)) {
                $verifier = new Captcha();
                if (!$verifier->verify($captcha)) {
                    throw AuthException::invalidCaptcha();
                }
            }
            AuthRepository::login(
                $email,
                $request->get('password'),
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