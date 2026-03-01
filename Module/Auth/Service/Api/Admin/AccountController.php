<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Repositories\AccountRepository;
use Module\Auth\Domain\Repositories\ApplyRepository;
use Module\Auth\Domain\Repositories\StatisticsRepository;

class AccountController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
            AccountRepository::logList($keywords, '', $user)
        );
    }

    public function rechargeAction(int $user, float $money, string $remark, int $type = 0) {
        try {
            AccountRepository::recharge($user, $money, $remark, $type);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function applyAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(ApplyRepository::getList($keywords, 0, $user));
    }

    public function applySaveAction(int $id, $status = 0) {
        try {
            $model = ApplyRepository::change($id, $status);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function userAction(int $id, string $extra = '') {
        try {
            $model = StatisticsRepository::user($id, $extra);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}