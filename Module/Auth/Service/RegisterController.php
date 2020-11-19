<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Infrastructure\Http\Request;

class RegisterController extends Controller {

    protected function rules() {
        return [
            'post' => 'p',
            '*' => '?'
        ];
    }

    public function indexAction() {
        return $this->show();
    }

    public function postAction(Request $request) {
        try {
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