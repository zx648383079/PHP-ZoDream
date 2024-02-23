<?php
declare(strict_types=1);
namespace Module\Auth\Service;

use Module\Auth\Domain\Repositories\PassKey;

class PasskeyController extends Controller {

    public function rules() {
        return [
            'index' => '?',
            'login' => '?',
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->renderData(PassKey::getLoginOption());
    }

    public function loginAction(array $credential, string $redirect_uri = '') {
        try {
            PassKey::login($_POST['credential']);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => url(empty($redirect_uri) ? '/' : $redirect_uri)
        ], '登录成功！');
    }

    public function registerOptionAction() {
        return $this->renderData(PassKey::getRegisterOption());
    }

    public function registerAction(array $credential) {
        try {
            PassKey::register($credential);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true, '注册生物识别成功');
    }

    public function twofaAction() {
        $this->layout = '';
        return $this->show(PassKey::create2FA());
    }

    public function twofaSaveAction(string $twofa_code) {
        try {
            PassKey::save2FA($twofa_code);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'refresh' => true
        ], '已开启两步验证');
    }
}