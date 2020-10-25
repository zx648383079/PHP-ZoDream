<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
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
            return $this->json([
                'code' => 302,
                'status' => __('failure'),
                'errors' => '重定向',
                'url' => url(['redirect_uri' => $request->referrer()])
            ]);
        }
        $time = strtotime(date('Y-m-d 00:00:00'));
        $num = LoginLogModel::failureCount($request->ip(), $time);
        if ($num > 2) {
            $num = intval($num / 3);
            $this->send('code', $num);
            Factory::session()->set('level', $num);
        }
        $redirect_uri = $request->get('redirect_uri');
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
     * @method GET, POST
     * @param $email
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function loginAction(Request $request, $email = null) {
        try {
            // 判断 ip 是否登录次数过多
            // 判断 账号 是否登录失败过多
            // 验证 验证码
            AuthRepository::login(
                $email,
                $request->get('password'),
                $request->has('rememberMe'));
        } catch (\Exception $ex) {
            // 是否需要显示验证码
            if (!empty($email)) {
                LoginLogModel::addLoginLog($email, 0, false);
            }
            return $this->jsonFailure($ex->getMessage());
        }
        $redirect_uri = $request->get('redirect_uri');
        return $this->jsonSuccess([
            'url' => url(empty($redirect_uri) ? '/' : $redirect_uri)
        ], '登录成功！');

    }

    public function logoutAction() {
        auth()->user()->logout();
        return $this->redirect('/');
    }
}