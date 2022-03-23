<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Repositories\UserRepository;
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
            'profile' => sprintf('%s::%s', UserRepository::class, 'getCurrentProfile'),
        ]));
    }
}