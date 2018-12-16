<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\LoginQrModel;
use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;
use Zodream\Image\QrCode;

class QrController extends ModuleController {

    protected function rules() {
        return [
            'authorize' => '@',
            '*' => '?',
        ];
    }

    public function indexAction() {
        $model = LoginQrModel::createNew();
        $image = new QrCode();
        $image->encode(url('./qr/authorize', ['token' => $model->token]));
        session()->set('login_qr', $model->id);
        return app('response')->image($image);
    }

    public function checkAction() {
        $id = session('login_qr');
        if (empty($id)) {
            return $this->jsonFailure('unknow error');
        }
        $model = LoginQrModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('USER_TIPS_QR_OVERTIME', 204);
        }
        if ($model->user_id > 0
            && $model->status == LoginQrModel::STATUS_SUCCESS) {
            $user = UserModel::findIdentity($model->user_id);
            $user->login();
            $user->logLogin(true, LoginLogModel::MODE_QR);
            return $this->jsonSuccess([
                'url' => url('/')
            ], '登陆成功');
        }
        if ($model->isExpired()) {
            return $this->jsonFailure('USER_TIPS_QR_OVERTIME', 204);
        }
        if ($model->status == LoginQrModel::STATUS_UN_SCAN) {
            return $this->jsonFailure('QR_UN_SCANNED', 201);
        }
        if ($model->status == LoginQrModel::STATUS_UN_CONFIRM) {
            return $this->jsonFailure('QR_UN_CONFIRM', 202);
        }
        return $this->jsonFailure('QR_REJECT', 203);
    }

    /**
     * @param $token
     * @param bool $confirm
     * @param bool $reject
     * @return \Zodream\Infrastructure\Http\Response
     * @throws \Exception
     */
    public function authorizeAction($token, $confirm = false, $reject = false) {
        $model = LoginQrModel::findByToken($token);
        if (empty($model) || $model->isExpired() ||
            !in_array($model->status, [LoginQrModel::STATUS_UN_SCAN, LoginQrModel::STATUS_UN_CONFIRM])) {
            return $this->redirect('/');
        }
        $model->user_id = auth()->id();
        if (!empty($confirm) && $model->status == LoginQrModel::STATUS_UN_CONFIRM) {
            $model->status = LoginQrModel::STATUS_SUCCESS;
            $model->save();
            return $this->redirect('/');
        }
        if (!empty($reject)) {
            $model->status = LoginQrModel::STATUS_REJECT;
            $model->save();
            return $this->redirect('/');
        }
        $model->status = LoginQrModel::STATUS_UN_CONFIRM;
        $model->save();
        $user = auth()->user();
        return $this->show(compact('user', 'token'));
    }
}