<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\SEO\Domain\Option;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class RegisterController extends Controller {

    public function rules() {
        return [
            'post' => 'p',
            '*' => '?'
        ];
    }

    public function indexAction() {
        if (Option::value('auth_close') > 0) {
            return $this->redirectWithMessage('/', __('Registration not allowed'));
        }
        return $this->show();
    }

    public function postAction(Request $request) {
        try {
            if (Option::value('auth_close') > 0) {
                throw AuthException::disableRegister();
            }
            AuthRepository::register(
                $request->get('name'),
                $request->get('email'),
                $request->get('password'),
                $request->get('rePassword'),
                $request->has('agree'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $redirect_uri = $request->get('redirect_uri');
        return $this->renderData([
            'url' => url(empty($redirect_uri) ? '/' : $redirect_uri)
        ], '注册成功！');
    }
}