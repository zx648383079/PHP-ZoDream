<?php
declare(strict_types=1);
namespace Module\Auth\Service;

use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class RegisterController extends Controller {

    public function rules() {
        return [
            'post' => 'p',
            '*' => '?'
        ];
    }

    public function indexAction() {
        if (AuthRepository::registerType() > 1) {
            return $this->redirectWithMessage('/', __('Registration not allowed'));
        }
        return $this->show();
    }

    public function postAction(Request $request) {
        try {
            if (AuthRepository::registerType() > 1) {
                throw AuthException::disableRegister();
            }
            AuthRepository::register(
                $request->get('name'),
                $request->get('email'),
                $request->get('password'),
                $request->get('rePassword'),
                $request->has('agree'), $request->get('invite_code'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $redirect_uri = $request->get('redirect_uri');
        return $this->renderData([
            'url' => url(empty($redirect_uri) ? '/' : $redirect_uri)
        ], '注册成功！');
    }

    public function verifyAction(string $email, string $code) {
        $this->layout = app_path('UserInterface/Home/layouts/main.php');
        $message = '';
        try {
            AuthRepository::verifyEmailAddress($email, $code);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }
        return $this->show(compact('message'));
    }
}