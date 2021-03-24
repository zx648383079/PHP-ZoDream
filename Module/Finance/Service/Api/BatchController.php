<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\AccountRepository;
use Module\Finance\Domain\Repositories\BudgetRepository;
use Module\Finance\Domain\Repositories\ChannelRepository;
use Module\Finance\Domain\Repositories\ProductRepository;
use Module\Finance\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class BatchController extends Controller {

    public function indexAction(Input $input) {
        $data = [];
        if ($input->has('account')) {
            $data['account'] = AccountRepository::all();
        }
        if ($input->has('budget')) {
            $data['budget'] = BudgetRepository::all();
        }
        if ($input->has('channel')) {
            $data['channel'] = ChannelRepository::all();
        }
        if ($input->has('project')) {
            $data['project'] = ProjectRepository::all();
        }
        if ($input->has('product')) {
            $data['product'] = ProductRepository::all();
        }
        return $this->render($data);
    }

}