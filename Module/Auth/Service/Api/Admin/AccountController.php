<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Repositories\AccountRepository;
use Zodream\Route\Controller\RestController;

class AccountController extends RestController {

    use AdminRole;

    public function indexAction(int $id) {
        $log_list = AccountLogModel::where('user_id', $id)->orderBy('id', 'desc')
            ->page();
        return $this->renderPage($log_list);
    }

    public function rechargeAction(int $user_id, float $money, string $remark, int $type = 0) {
        try {
            AccountRepository::recharge($user_id, $money, $remark, $type);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}