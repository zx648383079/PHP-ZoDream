<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api;

use Module\Catering\Domain\Repositories\StoreRepository;
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
            'profile' => sprintf('%s::profile', StoreRepository::class)
        ]));
    }
}