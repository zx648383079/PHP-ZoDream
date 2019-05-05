<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Cookie;
use Zodream\Module\OAuth\Domain\Client;
use Zodream\ThirdParty\OAuth\BaseOAuth;

class OauthController extends ModuleController {

    protected function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction($type = 'qq', $redirect_uri = '') {
        $auth = $this->getOAuth($type);
        if (!empty($redirect_uri)) {
            session()->set('redirect_uri', $redirect_uri);
        }
        return $this->redirect($auth->login());
    }

    public function callbackAction($type = 'qq') {
        $auth = $this->getOAuth($type);
        if (!$auth->callback()) {
            return $this->failureCallback('授权回调失败！');
        }
        $user = $this->findUser($type, $auth);
        if (!empty($user)) {
            return $this->successCallback($user, $type);
        }
        if (empty($auth->info())) {
            return $this->failureCallback('获取用户信息失败');
        }
        if (!auth()->guest()) {
            $user = auth()->user();
            $this->successBindUser($type, $user, $auth);
            return $this->successCallback($user);
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
        if (empty($user)) {
            return $this->failureCallback('系统错误！');
        }
        $this->successBindUser($type, $user, $auth);
        return $this->successCallback($user, $type);
    }

    protected function findUser($type, $auth) {
        if ($type == 'wechat' && $auth->unionid) {
            return OAuthModel::findUserWithUnion($auth->openid, $auth->unionid, $type);
        }
        return OAuthModel::findUser(
            $auth->identity,
            $type);
    }

    /**
     * @param $type
     * @param $user
     * @param $auth
     */
    protected function successBindUser($type, $user, $auth) {
        OAuthModel::bindUser($user, $auth->identity, $type, $auth->username);
        if ($type == 'wechat' && $auth->unionid) {
            OAuthModel::bindUser($user, $auth->openid, $type, $auth->username);
        }
    }

    protected function failureCallback($error) {
        $redirect_uri = session('redirect_uri');
        /** @var PlatformModel $platform */
        $platform = session('platform');
        if (empty($platform) || empty($redirect_uri)) {
            return $this->redirectWithMessage('/', $error);
        }
        $domain = parse_url($redirect_uri, PHP_URL_HOST);
        if ($domain === 'localhost') {
            $domain = false;
        }
        Cookie::set($platform->getCookieTokenKey(), json_encode([
            'code' => 400,
            'error' => $error
        ]), 600, null, $domain, false);
        return $this->redirect($redirect_uri);
    }

    protected function successCallback(UserModel $user, $type) {
        $redirect_uri = session('redirect_uri');
        /** @var PlatformModel $platform */
        $platform = session('platform');
        if (empty($platform) || empty($redirect_uri)) {
            $user->login();
            $user->logLogin(true, $type);
            return $this->redirect($redirect_uri ? $redirect_uri : '/');
        }
        $domain = parse_url($redirect_uri, PHP_URL_HOST);
        if ($domain === 'localhost') {
            $domain = false;
        }
        Cookie::set($platform->getCookieTokenKey(), json_encode([
            'code' => 200,
            'token' => $platform->generateToken($user)
        ]), 600, null, $domain, false);
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