<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Service\Factory;

class HomeController extends ModuleController {

    public function indexAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            return $this->redirect(Request::get('ReturnUrl', 'index.php'));
        }
        $time = TimeExpand::getBeginAndEndTime(TimeExpand::TODAY);
        $num = LoginLogModel::where(array(
            'ip' => Request::ip(),
            'status = 0',
            array(
                'create_at', 'between', $time[0], $time[1]
            )
        ))->count();
        if ($num > 2) {
            $num = intval($num / 3);
            $this->send('code', $num);
            Factory::session()->set('level', $num);
        }
        return $this->show(array(
            'title' => '用户登录'
        ));
    }

    public function checkAction() {
        list($name, $value) = Request::post('name,value');
        if (!in_array($name, ['username', 'email'])) {
            return $this->ajax([
                'code' => 1,
                'msg' => '查询失败！'
            ]);
        }
        $count = UserModel::find()->where([$name => $value])->count('id')->scalar();
        return $this->ajax([
            'code' => 0,
            'data' => $count > 0
        ]);
    }

    public function loginAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            return $this->ajax([
                'code' => 0
            ]);
        }
        return $this->ajax([
            'code' => 1,
            'msg' => '登录失败！',
            'errors' => $user->getError()
        ]);
    }


    public function registerAction() {
        return $this->show(array(
            'title' => '后台注册'
        ));
    }

    /**
     * @param string $type
     * @return BaseOAuth
     */
    protected function getOAuth($type = 'qq') {
        static $maps = [
            'qq' => 'QQ',
            'alipay' => 'ALiPay',
            'baidu' => 'BaiDu',
            'taobao' => 'TaoBao',
            'weibo' => 'WeiBo',
            'wechat' => 'WeChat',
            'github' => 'GitHub'
        ];
        $type = strtolower($type);
        if (!array_key_exists($type, $maps)) {
            throw new \InvalidArgumentException($type.' 的第三方登录组件不存在！');
        }
        $class = 'Zodream\\Domain\\ThirdParty\\OAuth\\'.$maps[$type];
        return new $class;
    }

    public function oauthAction($type = 'qq') {
        $oauth = $this->getOAuth($type);
        return $oauth->login();
    }

    public function logoutAction() {
        Auth::user()->logout();
        return $this->redirect('/');
    }
}