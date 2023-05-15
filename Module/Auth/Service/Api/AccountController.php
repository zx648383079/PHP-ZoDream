<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Repositories\AccountRepository;

class AccountController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {

    }

    public function logAction(string $keywords = '', string $type = '') {
        return $this->renderPage(
            AccountRepository::logList($keywords, $type)
        );
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
     * @throws \Exception
     */
    public function cancelAction(string $reason) {
        $user = auth()->user();
        AccountRepository::cancel($user, $reason);
        return $this->render($user);
    }

    public function loginLogAction(string $keywords = '') {
        $model_list = LoginLogModel::where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'ip');
            })
            ->orderBy('id', 'desc')->page();
        return $this->renderPage($model_list);
    }
}