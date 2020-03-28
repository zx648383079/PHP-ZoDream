<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class RegisterController extends RestController {

    protected function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction(Request $request) {
        try {
            AuthRepository::register(
                $request->get('name'),
                $request->get('email'),
                $request->get('password'),
                $request->get('rePassword'),
                $request->has('agree')
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $user = auth()->user();
        $token = auth()->createToken($user);
        event(new TokenCreated($token, $user));
        return $this->render(array_merge($user->toArray(), [
            'token' => $token
        ]));
    }
}