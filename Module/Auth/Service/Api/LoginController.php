<?php
namespace Module\Auth\Service\Api;


use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class LoginController extends RestController {

    protected function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction(Request $request) {
        $remember = $request->get('rememberMe')
            || $request->get('remember');
        try {
            if ($request->has('mobile')) {
                if ($request->has('code')) {
                    AuthRepository::loginMobileCode(
                        $request->get('email'),
                        $request->get('code'),
                        $remember, false);
                } else {
                    AuthRepository::loginMobile(
                        $request->get('email'),
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