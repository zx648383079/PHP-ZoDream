<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Platform;
use Zodream\Infrastructure\Cookie;
use Zodream\Module\OAuth\Domain\Client;
use Zodream\ThirdParty\OAuth\BaseOAuth;

class OauthController extends Controller {

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
        /** @var Platform $platform */
        $platform = session('platform');
        try {
            $auth = $this->getOAuth($type);
            if (!$auth->callback()) {
                return $this->failureCallback('授权回调失败！');
            }
            $user = AuthRepository::oauth($type, $auth->identity,
                function () use ($auth) {
                    if (empty($auth->info())) {
                        throw new \Exception('获取用户信息失败');
                    }
                    return [
                        $auth->username,
                        $auth->email,
                        $auth->sex == 'M' ? UserModel::SEX_MALE : UserModel::SEX_FEMALE,
                        $auth->avatar
                    ];
            }, $auth->unionid, !empty($platform) ? $platform->id() : 0);
        } catch (\Exception $ex) {
            return $this->failureCallback($ex->getMessage());
        }
        return $this->successCallback($user, $platform);
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

    protected function successCallback(UserModel $user, $platform) {
        $redirect_uri = session('redirect_uri');
        if (empty($platform) || empty($redirect_uri)) {
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