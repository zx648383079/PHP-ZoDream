<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\AccountRepository;
use Module\Finance\Domain\Repositories\BudgetRepository;
use Module\Finance\Domain\Repositories\ChannelRepository;
use Module\Finance\Domain\Repositories\ProductRepository;
use Module\Finance\Domain\Repositories\ProjectRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {

    use BatchAction;

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'account' => sprintf('%s::%s', AccountRepository::class, 'all'),
            'budget' => sprintf('%s::%s', BudgetRepository::class, 'all'),
            'channel' => sprintf('%s::%s', ChannelRepository::class, 'all'),
            'project' => sprintf('%s::%s', ProjectRepository::class, 'all'),
            'product' => sprintf('%s::%s', ProductRepository::class, 'all'),
        ]));
    }

}