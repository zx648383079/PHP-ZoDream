<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Events\TokenCreated;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\LoginQrModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Http\Uri;
use Zodream\Image\QrCode;
use Zodream\Infrastructure\Http\Output\RestResponse;
use Zodream\Infrastructure\Http\Response;
use Zodream\Route\Controller\RestController;

class QrController extends RestController {

    protected function methods() {
        return [
            'index' => ['POST'],
            'authorize' => ['POST']
        ];
    }

    protected function rules() {
        return [
            'refresh' => '?',
            'check' => '?',
            '*' => '@',
        ];
    }

    /**
     * 刷新二维码
     * @return RestResponse
     * @throws \Exception
     */
    public function refreshAction() {
        $model = LoginQrModel::createNew();
        $image = new QrCode();
        $image->encode($model->url);
        return $this->render([
           'token' => $model->token,
           'qr' => $image->toBase64(),
        ]);
    }

    public function checkAction($token) {
        $model = LoginQrModel::findIfToken($token);
        if (empty($model)) {
            return $this->renderFailure('USER_TIPS_QR_OVERTIME', 204);
        }
        if ($model->user_id > 0
            && $model->status == LoginQrModel::STATUS_SUCCESS) {
            $user = UserModel::findIdentity($model->user_id);
            $user->login();
            $user->logLogin(true, LoginLogModel::MODE_QR);
            $token = auth()->createToken($user);
            event(new TokenCreated($token, $user));
            $data = UserRepository::getCurrentProfile();
            $data['token'] = $token;
            return $this->render($data);
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
     * 验证token
     * @param $token
     * @return RestResponse
     * @throws \Exception
     */
    public function indexAction($token) {
        $model = LoginQrModel::findIfToken($token);
        if (empty($model) || $model->isExpired() ||
            !in_array($model->status, [LoginQrModel::STATUS_UN_SCAN, LoginQrModel::STATUS_UN_CONFIRM])) {
            return $this->renderFailure('二维码已失效');
        }
        return $this->render($model->toArray());
    }

    /**
     * @param $token
     * @param bool $confirm
     * @param bool $reject
     * @return RestResponse
     * @throws \Exception
     */
    public function authorizeAction($token, $confirm = false, $reject = false) {
        $model = LoginQrModel::findIfToken($token);
        if (empty($model) || $model->isExpired() ||
            !in_array($model->status, [LoginQrModel::STATUS_UN_SCAN, LoginQrModel::STATUS_UN_CONFIRM])) {
            return $this->renderFailure('二维码已失效');
        }
        $model->user_id = auth()->id();
        if (!empty($confirm) && $model->status == LoginQrModel::STATUS_UN_CONFIRM) {
            $model->status = LoginQrModel::STATUS_SUCCESS;
            $model->save();
            return $this->render($model->toArray());
        }
        if (!empty($reject)) {
            $model->status = LoginQrModel::STATUS_REJECT;
            $model->save();
            return $this->render($model->toArray());
        }
        $model->status = LoginQrModel::STATUS_UN_CONFIRM;
        $model->save();
        return $this->render($model->toArray());
    }
}