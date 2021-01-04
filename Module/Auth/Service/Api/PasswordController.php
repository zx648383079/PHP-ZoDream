<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PasswordController extends Controller {

    public function rules() {
        return [
            'update' => '@',
        ];
    }

    public function indexAction() {
        return $this->render([
        ]);
    }

    public function sendFindEmailAction($email) {
        try {
            AuthRepository::sendEmail(
                $email,
                Str::randomNumber(8)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'data' => true,
            'message' => sprintf('邮件已成功发送至 %s 请注意查收！', $email)
        ]);
    }

    public function sendMobileCodeAction($mobile, $type = 'login') {
        try {
            AuthRepository::sendSmsCode(
                $mobile,
                $type
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'data' => true,
            'message' => sprintf('验证码已成功发送至 %s 请注意查收！', $mobile)
        ]);
    }

    public function resetAction(Request $request) {
        try {
            AuthRepository::resetPassword(
                $request->get('code'),
                $request->get('password'),
                $request->get('confirm_password'),
                $request->get('email')
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function updateAction(Request $request) {
        try {
            AuthRepository::password(
                $request->get('old_password'),
                $request->get('password'),
                $request->get('confirm_password')
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}