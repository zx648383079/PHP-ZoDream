<?php
namespace Module\Auth\Service\Api;


use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Repositories\AuthRepository;
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
            AuthRepository::login(
                $request->get('email'),
                $request->get('password'),
                $remember);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $user = auth()->user();
        $token = auth()->createToken($user, $remember ? 365 * 86400 : 0);
        event(new TokenCreated($token, $user));
        return $this->render(array_merge($user->toArray(), [
            'token' => $token
        ]));
    }
}