<?php
declare(strict_types=1);
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\InviteRepository;
use Zodream\Image\QrCode;
use Zodream\Infrastructure\Contracts\Http\Output;

class QrController extends Controller {

    public function rules() {
        return [
            'authorize' => '@',
            '*' => '?',
        ];
    }

    public function indexAction(Output $output) {
        $token = InviteRepository::createNew(InviteRepository::TYPE_LOGIN, 1, 300);
        $image = new QrCode();
        $image->encode(InviteRepository::loginQr($token));
        session()->set('login_qr', $token);
        return $output->image($image);
    }

    public function checkAction() {
        $id = session('login_qr');
        if (empty($id)) {
            return $this->renderFailure('unknow error');
        }
        try {
            $items = InviteRepository::checkQr(InviteRepository::TYPE_LOGIN, $id);
            if (empty($items)) {
                return $this->renderFailure('unknow error');
            }
            $user = UserModel::findIdentity($items[0]);
            $user->login();
            $user->logLogin(true, LoginLogModel::MODE_QR);
            return $this->renderData([
                'url' => url('/')
            ], '登陆成功');
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * @param string $token
     * @param bool $confirm
     * @param bool $reject
     * @return Output
     * @throws \Exception
     */
    public function authorizeAction(string $token, bool $confirm = false, bool $reject = false) {
        try {
            $res = InviteRepository::authorize(InviteRepository::TYPE_LOGIN, $token, $confirm, $reject);
            $user = auth()->user();
            return is_bool($res) ? $this->redirect('/') :
                $this->show(compact('user', 'token'));
        } catch (\Exception $ex) {
            return $this->redirect('/');
        }
    }
}