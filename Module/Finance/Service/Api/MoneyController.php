<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Repositories\AccountRepository;
use Module\Finance\Domain\Repositories\ProjectRepository;

class MoneyController extends Controller {

    public function indexAction() {
        $account_list = AccountRepository::all();
        $total = 0;
        foreach ($account_list as $item) {
            $total += $item->total;
        }
        $product_list = ProjectRepository::all();
        $project_list = FinancialProjectModel::auth()->select('name', 'money')->all();
        return $this->render(compact('account_list', 'total', 'product_list', 'project_list'));
    }


}