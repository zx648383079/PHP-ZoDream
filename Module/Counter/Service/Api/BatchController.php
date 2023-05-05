<?php
declare(strict_types=1);
namespace Module\Counter\Service\Api;

use Module\Counter\Domain\Repositories\StatisticsRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {
    use BatchAction;

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'today' => sprintf('%s::%s', StatisticsRepository::class, 'today'),
        ]));
    }
}