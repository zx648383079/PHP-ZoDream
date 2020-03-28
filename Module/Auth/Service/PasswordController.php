<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Repositories\AuthRepository;
use Module\ModuleController;
use Zodream\Helpers\Str;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Http\Request;

class PasswordController extends ModuleController {

    public function indexAction($code) {
        try {
            AuthRepository::verifyEmailCode($code);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('/', '密码找回失败');
        }
        return $this->show(compact('code'));
    }

    public function findAction() {
        return $this->show();
    }

    public function resetAction(Request $request) {
        try {
            AuthRepository::resetPassword(
                $request->get('code'),
                $request->get('password'),
                $request->get('rePassword')
            );
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess([
            'url' => url('./')
        ]);
    }

    public function sendAction($email) {
        try {
            AuthRepository::sendEmail($email,
                md5($email. Str::random(). Time::millisecond())
                );
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess(null, sprintf('邮件已成功发送至 %s 请注意查收！', $email));
    }


}