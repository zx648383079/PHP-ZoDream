<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\IAuthPlatform;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\ThirdParty\WeChat\MiniProgram\OAuth as MiniOAuth;
use Module\Auth\Service\OauthController as BaseController;

class OauthController extends Controller {

    public function indexAction(string $type = 'qq', string $redirect_uri = '') {
        url()->setModulePath(config()->getModulePath(self::class));
        session(['platform' => app('platform')]);
        return (new BaseController())->indexAction($type, $redirect_uri);
    }

    public function miniAction(string $code, string $nickname, string $avatar = '', int $gender = 2) {
        /** @var IAuthPlatform $platform */
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
                }, $data['unionid'] ?? null,
                $platform->id()
            );
            AuthRepository::updateOAuthData(OAuthModel::TYPE_WX_MINI,
                $data['openid'],  $data['session_key'], $data['unionid'] ?? null,
                $platform->id());
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
     * @return Output
     * @throws \Exception
     */
    public function miniLoginAction(string $code) {
        /** @var IAuthPlatform $platform */
        $platform = app('platform');
        $mini = new MiniOAuth($platform->option('mini_auth'));
        try {
            $data = $mini->login($code);
            AuthRepository::updateOAuthData(OAuthModel::TYPE_WX_MINI,
                $data['openid'],  $data['session_key'], $data['unionid'] ?? null,
                $platform->id());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'openid' => $data['openid']
        ]);
    }

    public function miniDecryptAction(Request $request, string $data, string $iv) {
        /** @var IAuthPlatform $platform */
        $platform = app('platform');
        $mini = new MiniOAuth($platform->option('mini_auth'));
        try {
            $sessionKey = OAuthModel::where('vendor', OAuthModel::TYPE_WX_MINI)
                ->where('platform_id', $platform->id())
                ->when(auth()->guest(), function ($query) use ($request) {
                    $query->where('platform_id', $request->get('openid'));
                }, function ($query) {
                    $query->where('user_id', auth()->id());
                })->value('data');
            if (empty($sessionKey)) {
                throw new \Exception('请使用wx.login');
            }
            $data = $mini->set('sessionKey', $sessionKey)->decrypt($data, $iv);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($data);
    }
}