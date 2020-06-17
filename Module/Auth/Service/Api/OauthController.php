<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\OpenPlatform\Domain\Platform;
use Zodream\Infrastructure\Http\Output\RestResponse;
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
        /** @var Platform $platform */
        $platform = app('platform');
        $mini = new MiniOAuth($platform->option('mini_auth'));
        $guest = auth()->guest();
        try {
            $data = $mini->login($code);
            $user = AuthRepository::oauth(
                OAuthModel::TYPE_WX_MINI,
                $data['openid'],
                function () use ($nickname, $gender, $avatar) {
                    return [
                        $nickname,
                        null,
                        $gender == 1 ? UserModel::SEX_MALE : UserModel::SEX_FEMALE,
                        $avatar
                    ];
                }, isset($data['unionid']) ? $data['unionid'] : null,
                $platform->id()
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $data = UserRepository::getCurrentProfile();
        if (!$guest) {
            return $this->render($data);
        }
        $data['token'] = auth()->createToken($user);
        event(new TokenCreated($data['token'], $user));
        return $this->render($data);
    }

    /**
     * 获取openid
     * @param $code
     * @return RestResponse
     * @throws \Exception
     */
    public function miniLoginAction($code) {
        /** @var Platform $platform */
        $platform = app('platform');
        $mini = new MiniOAuth($platform->option('mini_auth'));
        try {
            $data = $mini->login($code);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'openid' => $data['openid']
        ]);
    }
}