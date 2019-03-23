<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Helpers\Str;
use Zodream\Route\Controller\RestController;
use Zodream\ThirdParty\WeChat\MiniProgram\OAuth as MiniOAuth;
use Module\Auth\Service\OauthController as BaseController;

class OauthController extends RestController {

    public function indexAction($type = 'qq', $redirect_uri = '') {
        url()->setModulePath(config()->getModulePath(self::class));
        session(['platform' => app('platform')]);
        return (new BaseController())->indexAction($type, $redirect_uri);
    }

    public function miniAction($code, $nickname, $avatar = '', $gender = 2) {
        $user = UserModel::find(1);
        return $this->render(array_merge($user->toArray(), [
            'token' => auth()->createToken($user)
        ]));
        
        $mini = new MiniOAuth();
        $data = $mini->login($code);
        $user = OAuthModel::findUser(
            $data['openid'],
            OAuthModel::TYPE_WX_MINI);
        if (!empty($user)) {
            return $this->render(array_merge($user->toArray(), [
                'token' => auth()->createToken($user)
            ]));
        }
        $rnd = Str::random(3);
        $email = sprintf('%s_%s@zodream.cn',
            OAuthModel::TYPE_WX_MINI, $data['openid']);
        $user = UserModel::create([
            'name' => $nickname,
            'email' => $email,
            'password' => $rnd,
            'sex' => $gender == 1 ? UserModel::SEX_MALE : UserModel::SEX_FEMALE,
            'avatar' => $avatar
        ]);
        OAuthModel::bindUser($user, $data['openid'],
            OAuthModel::TYPE_WX_MINI);
        return $this->render(array_merge($user->toArray(), [
            'token' => auth()->createToken($user)
        ]));
    }
}