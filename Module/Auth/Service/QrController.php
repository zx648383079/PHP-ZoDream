<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\LoginQrModel;
use Module\Auth\Domain\Model\UserModel;
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
        $model = LoginQrModel::createNew();
        $image = new QrCode();
        $image->encode($model->url);
        session()->set('login_qr', $model->id);
        return $output->image($image);
    }

    public function checkAction() {
        $id = session('login_qr');
        if (empty($id)) {
            return $this->renderFailure('unknow error');
        }
        $model = LoginQrModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('USER_TIPS_QR_OVERTIME', 204);
        }
        if ($model->user_id > 0
            && $model->status == LoginQrModel::STATUS_SUCCESS) {
            $user = UserModel::findIdentity($model->user_id);
            $user->login();
            $user->logLogin(true, LoginLogModel::MODE_QR);
            return $this->renderData([
                'url' => url('/')
            ], '登陆成功');
        }
        if ($model->isExpired()) {
            return $this->renderFailure('USER_TIPS_QR_OVERTIME', 204);
        }
        if ($model->status == LoginQrModel::STATUS_UN_SCAN) {
            return $this->renderFailure('QR_UN_SCANNED', 201);
        }
        if ($model->status == LoginQrModel::STATUS_UN_CONFIRM) {
            return $this->renderFailure('QR_UN_CONFIRM', 202);
        }
        return $this->renderFailure('QR_REJECT', 203);
    }

    /**
     * @param $token
     * @param bool $confirm
     * @param bool $reject
     * @throws \Exception
     */
    public function authorizeAction($token, $confirm = false, $reject = false) {
        $model = LoginQrModel::findIfToken($token);
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