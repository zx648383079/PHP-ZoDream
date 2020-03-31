<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AccountRepository;
use Zodream\Infrastructure\Http\Output\RestResponse;
use Zodream\Route\Controller\RestController;

class AccountController extends RestController {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
    }

    public function logAction() {
        $log_list = AccountLogModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        return $this->renderPage($log_list);
    }


    public function connectAction() {
        $model_list = AccountRepository::getConnect();
        return $this->render($model_list);
    }

    public function driverAction() {
        return $this->render(['data' => []]);
    }

    public function subtotalAction() {
        return $this->render([
           'money' => 0,
           'integral' => 0,
           'bonus' => 0,
           'coupon' => 0
        ]);
    }

    /**
     * 注销账户
     * @param $reason
     * @return RestResponse
     * @throws \Exception
     */
    public function cancelAction($reason) {
        $user = auth()->user();
        $user->status = UserModel::STATUS_FROZEN;
        $user->save();
        BulletinModel::system(1, '账户注销申请',
            sprintf('申请用户：%s，注销理由：%s <a href="%s">马上查看</a>', $user->name,
                $reason, url('/auth/admin/user/edit', ['id' => $user->id])), 98);
        return $this->render($user);
    }
}