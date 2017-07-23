<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Service\Factory;
use Zodream\Service\Routing\Url;

class HomeController extends ModuleController {

    protected function rules() {
        return [
            '*' => '?'
        ];
    }

    public function indexAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            return $this->redirect(Request::get('redirect_uri', '/'));
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
        $redirect_uri = Request::get('redirect_uri');
        $title = '用户登录';
        return $this->show(compact('redirect_uri', 'title'));
    }

    public function checkAction() {
        list($name, $value) = Request::post('name,value');
        if (!in_array($name, ['username', 'email'])) {
            return $this->jsonFailure('查询失败！');
        }
        $count = UserModel::find()->where([$name => $value])->count('id')->scalar();
        return $this->jsonSuccess($count > 0);
    }

    public function loginActionJson() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            $redirect_uri = Request::request('redirect_uri');
            return $this->jsonSuccess([
                'url' => (string)Url::to(empty($redirect_uri) ? '/' : $redirect_uri)
            ], '登录成功！');
        }
        return $this->jsonFailure($user->getFirstError());
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