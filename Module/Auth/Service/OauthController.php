<?php
namespace Module\Auth\Service;

use Carbon\Carbon;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;

use Zodream\Helpers\Str;

use Zodream\Module\OAuth\Domain\Client;
use Zodream\Service\Factory;

use Zodream\ThirdParty\OAuth\BaseOAuth;

class OauthController extends ModuleController {

    protected function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction($type = 'qq') {
        $auth = $this->getOAuth($type);
        session()->set('redirect_uri', app('request')->get('redirect_uri'));
        return $this->redirect($auth->login());
    }

    public function callbackAction($type = 'qq') {
        $auth = $this->getOAuth($type);
        if (!$auth->callback()) {
            return $this->redirectWithMessage('/', '授权回调失败！');
        }
        $redirect_uri = session('redirect_uri');
        if (empty($redirect_uri)) {
            $redirect_uri = '/';
        }
        $user = OAuthModel::findUser(
            $auth->identity,
            $type);
        if (!empty($user)) {
            $user->login();
            return $this->redirect($redirect_uri);
        }
        if (empty($auth->info())) {
            return $this->redirectWithMessage('/', '获取用户信息失败');
        }
        if (!auth()->guest()) {
            $user = auth()->user();
            OAuthModel::bindUser($user, $auth->identity, $type);
            return $this->redirect($redirect_uri);
        }
        $rnd = Str::random(3);
        $email = sprintf('%s_%s@zodream.cn', $type, $rnd);
        if (!empty($auth->email)
//            && ($user = UserModel::findByEmail($auth->email))) {
//            OAuthModel::bindUser($user, $auth->identity, $type);
//            $user->login();
//            return $this->redirect('/');
            && UserModel::validateEmail($auth->email) ) {
            $email = $auth->email;
        }

        $user = UserModel::create([
            'name' => empty($auth->username) ? '用户_'.$rnd : $auth->username ,
            'email' => $email,
            'password' => $rnd,
            'sex' => $auth->sex == 'M' ? UserModel::SEX_MALE : UserModel::SEX_FEMALE,
            'avatar' => $auth->avatar
        ]);
        OAuthModel::bindUser($user, $auth->identity, $type);
        $user->login();
        return $this->redirect($redirect_uri);
    }

    /**
     * @param string $type
     * @return BaseOAuth
     */
    protected function getOAuth($type = 'qq') {
        if ($type == 'zd') {
            return new Client([
                'client_id' => '101321779',
                'client_secret' => 'cedee2ac4df08975927b638098e08e1b',
                'redirect_uri' => url('./oauth/callback', [
                    'type' => $type
                ])
            ]);
        }
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
        $class = 'Zodream\\ThirdParty\\OAuth\\'.$maps[$type];
        return new $class;
    }
}