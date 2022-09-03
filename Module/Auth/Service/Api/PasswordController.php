<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\VerifyCodeRepository;
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

    public function sendFindEmailAction(string $email) {
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

    public function sendMobileCodeAction(string $mobile, string $type = 'login') {
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
            $data = $request->validate([
                'old_password' => '',
                'verify_type' => '',
                'verify' => '',
                'password' => 'required',
                'confirm_password' => 'required',
            ]);
            if (isset($data['old_password'])) {
                $data['verify_type'] = 'password';
                $data['verify'] = $data['old_password'];
            }
            AuthRepository::password($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function sendCodeAction(Request $input) {
        try {
            $data = VerifyCodeRepository::sendCode($input->validate([
                'to_type' => 'required',
                'to' => '',
                'event' => 'required',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'data' => true,
            'message' => sprintf('验证码已成功发送至 %s 请注意查收！', $data['to'])
        ]);
    }

    public function verifyCodeAction(Request $input) {
        try {
            VerifyCodeRepository::verifyCode($input->validate([
                'to_type' => 'required',
                'to' => '',
                'code' => 'required',
                'event' => 'required',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}