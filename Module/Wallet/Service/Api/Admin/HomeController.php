<?php
declare(strict_types=1);
namespace Module\Wallet\Service\Api\Admin;

use Module\Wallet\Domain\Repositories\WalletRepository;

class HomeController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
            WalletRepository::logList($keywords, '', $user)
        );
    }

    public function rechargeAction(int $user, float $money, string $remark, int $type = 0) {
        try {
            WalletRepository::recharge($user, $money, $remark, $type);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}