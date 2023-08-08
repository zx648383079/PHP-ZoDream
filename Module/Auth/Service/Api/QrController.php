<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\InviteRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Image\QrCode;
use Zodream\Infrastructure\Contracts\Http\Output;

class QrController extends Controller {

    public function methods() {
        return [
            'index' => ['POST'],
            'authorize' => ['POST']
        ];
    }

    public function rules() {
        return [
            'refresh' => '?',
            'check' => '?',
            '*' => '@',
        ];
    }

    /**
     * 刷新二维码
     * @throws \Exception
     */
    public function refreshAction() {
        $token = InviteRepository::createNew(InviteRepository::TYPE_LOGIN, 1, 300);
        $image = new QrCode();
        $image->encode(InviteRepository::loginQr($token));
        return $this->render([
           'token' => $token,
           'qr' => $image->toBase64(),
        ]);
    }

    public function checkAction(string $token) {
        try {
            $items = InviteRepository::checkQr(InviteRepository::TYPE_LOGIN, $token);
            if (empty($items)) {
                return $this->renderFailure('unknow error');
            }
            $user = UserModel::findIdentity($items[0]);
            $user->login();
            $user->logLogin(true, LoginLogModel::MODE_QR);
            $token = auth()->createToken($user);
            event(new TokenCreated($token, $user));
            $data = UserRepository::getCurrentProfile();
            $data['token'] = $token;
            return $this->render($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage(), $ex->getCode(), 400);
        }
    }

    /**
     * @param string $token
     * @param bool $confirm
     * @param bool $reject
     * @return Output
     */
    public function authorizeAction(string $token, bool $confirm = false, bool $reject = false) {
        try {
            $res = InviteRepository::authorize(InviteRepository::TYPE_LOGIN, $token, $confirm, $reject);
            return is_bool($res) ? $this->renderData($res) : $this->render([
                'message' => '授权登录',
            ]);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}