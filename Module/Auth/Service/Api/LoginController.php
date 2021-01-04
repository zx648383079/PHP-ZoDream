<?php
namespace Module\Auth\Service\Api;


use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class LoginController extends Controller {

    public function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction(Request $request) {
        $remember = $request->get('rememberMe')
            || $request->get('remember');
        $mobile = $request->get('mobile');
        $account = !empty($mobile) ?
            $mobile : $request->get('email');
        $captchaKey = $request->get('captcha_token');
        try {
            $captcha = $request->get('captcha');
            AuthRepository::loginPreCheck($request->ip(), $account, $captcha);
            if (!empty($captcha)) {
                if (empty($captchaKey)) {
                    throw AuthException::invalidCaptcha();
                }
                $captchaCode = cache()->store('captcha')->get($captchaKey);
                if (empty($captchaCode) || strtolower($captcha) !== $captcha) {
                    throw AuthException::invalidCaptcha();
                }
            }
            if (!empty($mobile)) {
                $code = $request->get('code');
                if (!empty($code)) {
                    AuthRepository::loginMobileCode(
                        $mobile,
                        $code,
                        $remember, false);
                } else {
                    AuthRepository::loginMobile(
                        $mobile,
                        $request->get('password'),
                        $remember, false);
                }
            } else {
                AuthRepository::login(
                    $request->get('email'),
                    $request->get('password'),
                    $remember, false);
            }

        } catch (\Exception $ex) {
            if (!empty($account) && $ex->getCode() < 1009) {
                LoginLogModel::addLoginLog($account, 0, false);
            }
            if ($ex->getCode() === 1009) {
                return $this->renderFailure([
                    'message' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                    'captcha_token' => !empty($captchaKey) ? $captchaKey : md5($request->ip().Time::millisecond())
                ]);
            }
            return $this->renderFailure($ex->getMessage());
        }
        $user = auth()->user();
        $refreshTTL = $remember ? 365 * 86400 : 0;
        $token = auth()->createToken($user, $refreshTTL);
        event(new TokenCreated($token, $user,
            $refreshTTL + auth()->getConfigs('refreshTTL')));
        $data = UserRepository::getCurrentProfile();
        $data['token'] = $token;
        return $this->render($data);
    }
}