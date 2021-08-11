<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\SEO\Domain\Option;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class RegisterController extends Controller {

    public function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction(Request $request) {
        try {
            if (Option::value('auth_close') > 0) {
                throw AuthException::disableRegister();
            }
            $mobile = $request->get('mobile');
            if (!empty($mobile)) {
                AuthRepository::registerMobile(
                    $request->get('name'),
                    $mobile,
                    $request->get('code'),
                    $request->get('password'),
                    $request->get('rePassword') ?: $request->get('confirm_password'),
                    $request->has('agree')
                );
            } else {
                AuthRepository::register(
                    $request->get('name'),
                    $request->get('email'),
                    $request->get('password'),
                    $request->get('rePassword') ?: $request->get('confirm_password'),
                    $request->has('agree')
                );
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $user = auth()->user();
        $token = auth()->createToken($user);
        event(new TokenCreated($token, $user));
        $data = UserRepository::getCurrentProfile();
        $data['token'] = $token;
        return $this->render($data);
    }
}