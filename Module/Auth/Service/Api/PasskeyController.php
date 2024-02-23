<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Repositories\PassKey;
use Module\Auth\Domain\Repositories\UserRepository;

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

    public function loginAction(array $credential) {
        try {
            PassKey::login($credential);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        $user = auth()->user();
        $refreshTTL = 0;
        $token = auth()->createToken($user, $refreshTTL);
        event(new TokenCreated($token, $user,
            $refreshTTL + auth()->getConfigs('refreshTTL')));
        $data = UserRepository::getCurrentProfile();
        $data['token'] = $token;
        return $this->render($data);
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
        return $this->render(PassKey::create2FA());
    }

    public function twofaSaveAction(string $twofa_code) {
        try {
            PassKey::save2FA($twofa_code);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true);
    }
}