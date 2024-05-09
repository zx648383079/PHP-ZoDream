<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\CaptchaRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\OpenPlatform\Domain\Platform;
use Zodream\Helpers\Security\Encryptor;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class LoginController extends Controller {

    public function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction(Request $request) {
        $remember = $request->bool('rememberMe')
            || $request->bool('remember');
        $mobile = $request->string('mobile');
        $account = !empty($mobile) ?
            $mobile : $request->string('email');
        $captchaKey = $request->string('captcha_token');
        $encryptor = new Encryptor(Platform::current()->requestTime());
        try {
            $captcha = $request->string('captcha');
            AuthRepository::loginPreCheck($request->ip(), $account, $captcha);
            if (!empty($captcha) && (empty($captchaKey) || !CaptchaRepository::verify($captcha, $captchaKey))) {
                throw AuthException::invalidCaptcha();
            }
            if (!empty($mobile)) {
                $code = $request->string('code');
                if (!empty($code)) {
                    AuthRepository::loginMobileCode(
                        $mobile,
                        $code,
                        $remember, false);
                } else {
                    AuthRepository::loginMobile(
                        $mobile,
                        $encryptor->decrypt($request->string('password')),
                        $remember, false);
                }
            } else {
                AuthRepository::login(
                    $request->string('email'),
                    $encryptor->decrypt($request->string('password')),
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
                    'captcha_token' => CaptchaRepository::token($captchaKey)
                ]);
            } elseif ($ex->getCode() === 1015) {
                return $this->renderFailure([
                    'message' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ]);
            }
            return $this->renderFailure($ex);
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

    public function refreshAction() {
        $token = auth()->refreshToken();
        if (empty($token)) {
            return $this->renderFailure('token is expired');
        }
        return $this->render(compact('token'));
    }
}