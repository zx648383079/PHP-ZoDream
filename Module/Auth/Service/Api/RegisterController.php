<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\OpenPlatform\Domain\Platform;
use Zodream\Helpers\Security\Encryptor;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class RegisterController extends Controller {

    public function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction(Request $request) {
        $encryptor = new Encryptor(Platform::current()->requestTime());
        try {
            if (AuthRepository::registerType() > 1) {
                throw AuthException::disableRegister();
            }
            $mobile = $request->get('mobile');
            if (!empty($mobile)) {
                AuthRepository::registerMobile(
                    $request->get('name'),
                    $mobile,
                    $request->get('code'),
                    $encryptor->decrypt($request->get('password')),
                    $encryptor->decrypt($request->get('rePassword') ?: $request->get('confirm_password')),
                    $request->has('agree'), $request->get('invite_code')
                );
            } else {
                AuthRepository::register(
                    $request->get('name'),
                    $request->get('email'),
                    $encryptor->decrypt($request->get('password')),
                    $encryptor->decrypt($request->get('rePassword') ?: $request->get('confirm_password')),
                    $request->has('agree'), $request->get('invite_code')
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