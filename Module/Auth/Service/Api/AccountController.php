<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
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
        return $this->renderData(AccountRepository::getConnect());
    }

    public function driverAction() {
        return $this->renderData(AccountRepository::getDriver());
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
    public function cancelAction(string $reason) {
        $user = auth()->user();
        AccountRepository::cancel($user, $reason);
        return $this->render($user);
    }

    public function loginLogAction($keywords = null) {
        $model_list = LoginLogModel::where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                LoginLogModel::searchWhere($query, 'ip');
            })
            ->orderBy('id desc')->page();
        return $this->renderPage($model_list);
    }
}